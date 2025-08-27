<?php
namespace CatFolders\Blocks;

use CatFolders\Traits\Singleton;

class GalleryBlock {
	use Singleton;

	private $script_handle = 'catfolders-image-gallery';

	public function __construct() {
		add_action( 'init', array( $this, 'create_block_catfolders_block_init' ) );
	}

	public function create_block_catfolders_block_init() {
		wp_register_style( 'catf-photoswipe', CATF_PLUGIN_URL . 'assets/css/photoswipe/photoswipe.css', array(), CATF_VERSION );
        wp_register_style( 'catf-photoswipe-default-skin', CATF_PLUGIN_URL . 'assets/css/photoswipe/default-skin.css', array(), CATF_VERSION );
    
        wp_register_script( 'catf-photoswipe', CATF_PLUGIN_URL . 'assets/js/photoswipe/photoswipe.min.js', array(), CATF_VERSION, true );
        wp_register_script( 'catf-photoswipe-ui-default', CATF_PLUGIN_URL . 'assets/js/photoswipe/photoswipe-ui-default.min.js', array(), CATF_VERSION, true );
        wp_register_script( 'catf-gallery', CATF_PLUGIN_URL . 'assets/js/photoswipe/catf-photoswipe.js', array(), CATF_VERSION, true );

		register_block_type( __DIR__ . '/build', array( 'render_callback' => array( $this, 'render_block' ) ) );
	}

	public function render_block( $attributes ) {
		if ( empty( $attributes['folders'] ) ) {
			return '';
		}

		if ( isset( $attributes['enableLightbox'] ) && $attributes['enableLightbox'] ) {
			wp_enqueue_style( 'catf-photoswipe' );
			wp_enqueue_style( 'catf-photoswipe-default-skin' );
			wp_enqueue_script( 'catf-photoswipe' );
			wp_enqueue_script( 'catf-photoswipe-ui-default' );
			wp_enqueue_script( 'catf-gallery' );
		}

		wp_enqueue_style( $this->script_handle, CATF_PLUGIN_URL . 'includes/Blocks/build/style-index.css', array(), CATF_VERSION );

		return $this->generate_html( $attributes );
	}

	public function get_attachments( $args ) {
		$selectedFolders = isset( $args['folders'] ) ? array_map( 'intval', $args['folders'] ) : array();
		if ( ! $selectedFolders ) {
			return false;
		}

		global $wpdb;
		$ids         = $selectedFolders;
		$where_arr[] = '`folder_id` IN (' . implode( ',', $ids ) . ')';
		$in_not_in   = $wpdb->get_col( "SELECT `post_id` FROM {$wpdb->prefix}catfolders_posts" . ' WHERE ' . implode( ' AND ', $where_arr ) );
		if ( ! $in_not_in ) {
			return false;
		}

		$queryArgs = array(
			'post_type'      => 'attachment',
			'post__in'       => $in_not_in,
			'posts_per_page' => -1,
			'orderby'        => array(
				'ID' => 'ASC',
			),
			'post_status'    => 'inherit',
		);

		$query = new \WP_Query( $queryArgs );

		$posts = $query->get_posts();

		if ( count( $posts ) < 1 ) {
			return '';
		}

		$attachments_data = array();
		foreach ( $posts as $post ) {

			if ( ! wp_attachment_is_image( $post ) ) {
				continue;
			}
			$attachment_data        = array();
			$attachment_data['id']  = $post->ID;

			$imageSrc               = wp_get_attachment_image_src( $post->ID, 'full' );
			$attachment_data['src'] = $imageSrc[0];
			$attachment_data['width'] = $imageSrc[1];
			$attachment_data['height'] = $imageSrc[2];

			$imageAlt               = get_post_meta( $post->ID, '_wp_attachment_image_alt', true );
			$imageAlt               = empty( $imageAlt ) ? $post->post_title : $imageAlt;
			$attachment_data['alt'] = $imageAlt;

			$imageCaption               = wp_get_attachment_caption( $post->ID );
			$attachment_data['caption'] = $imageCaption;

			$attachments_data[] = $attachment_data;

		}

		return $attachments_data;
	}

	public function generate_html( $attributes ) {
		$attachments = $this->get_attachments( $attributes );
		$html        = '';
		if ( $attachments && '' !== $attachments ) {
			$enableLightbox  = isset( $attributes['enableLightbox'] ) ? $attributes['enableLightbox'] : false;

			$html    .= '<div class="wp-block-catfolders-block-catfolders-gallery catf-wp-block-gallery">';

			$ulClass = '';
			$ulClass .= ! empty( $attributes['className'] ) ? ' ' . esc_attr( $attributes['className'] ) : '';
			
			if($attributes['layout'] == 'masonry') {
				$ulClass .= ' catf-blocks-gallery-grid is-style-masonry';
			} elseif($attributes['layout'] == 'grid') {
				$ulClass .= ' catf-blocks-gallery-grid is-style-grid';
			} else {
				//flex
				$ulClass .= 'catf-blocks-gallery-flex';
			}

			$ulClass .= ' catf-columns-' . esc_attr( $attributes['columns'] );

			if ( $enableLightbox ) {
				$ulClass .= ' catf-gallery-lightbox';
			}

			$html .= '<ul class="' . esc_attr( $ulClass ) . '">';
			foreach ( $attachments as $attachment ) {
				$img_attributes = 'src="' . esc_attr( $attachment['src'] ) . '" alt="' . esc_attr( $attachment['alt'] ) . '"';
				
				// Add width and height if available
				if ( ! empty( $attachment['width'] ) && ! empty( $attachment['height'] ) ) {
					$img_attributes .= ' width="' . esc_attr( $attachment['width'] ) . '" height="' . esc_attr( $attachment['height'] ) . '"';
				}
				
				$img = '<img ' . $img_attributes . '>';
				
				$caption = $attachment['caption'] ? '<figcaption class="wp-block-image-caption">' . esc_html( $attachment['caption'] ) . '</figcaption>' : '';
				$li      = '<li class="catf-blocks-gallery-item wp-block-image">';
				$li     .= '<figure>';
				if ( 'attachment' === $attributes['thumbnailLink'] || 'media_file' === $attributes['thumbnailLink'] ) {
					$thumbnailLinkTarget = in_array( $attributes['thumbnailLinkTarget'], array( '_self', '_blank' ) ) ? $attributes['thumbnailLinkTarget'] : '_self';
					$link = 'attachment' === $attributes['thumbnailLink'] ? get_attachment_link( $attachment['id'] ) : wp_get_attachment_url( $attachment['id'] );
					$li   .= '<a href="' . esc_url( $link ) . '" target="' . esc_attr( $thumbnailLinkTarget ) . '">';
				}
				$li     .= $img;

				if ( 'attachment' === $attributes['thumbnailLink'] || 'media_file' === $attributes['thumbnailLink'] ) {
					$li .= '</a>';
				}
				if( $attributes['caption'] === true ) {
					$li .= $caption;
				}

				$li .= '</figure>';
				$li .= '</li>';

				$html .= $li;
			}
			$html .= '</ul>';
			$html .= '</div>';
		}
		return $html;
	}
}


