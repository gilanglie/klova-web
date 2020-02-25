<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_WCPO_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_Pre_Order_Date_End_Email
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Carlos Mora <carlos.eugenio@yourinspiration.it>
 *
 */

if ( ! class_exists( 'YITH_Pre_Order_Date_End_Email' ) ) {
	/**
	 * Class YITH_Pre_Order_Date_End_Email
	 *
	 * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
	 */
	class YITH_Pre_Order_Date_End_Email extends WC_Email {

		public $email_body;

		public function __construct() {

			$this->id = 'yith_ywpo_date_end';

			$this->title         = __( 'YITH Pre-Order: Pre-order date is about to pass', 'yith-woocommerce-pre-order' );
			$this->description   = __( 'The administrator will receive an email before the Pre-order date passes.', 'yith-woocommerce-pre-order' );
			$this->heading       = __( 'Pre-Order products are going to end!', 'yith-woocommerce-pre-order' );
			$this->subject       = __( 'Pre-Order products are going to end', 'yith-woocommerce-pre-order' );
			$this->email_body    = __( 'Hi Admin, these Pre-Order products are about to expire:', 'yith-woocommerce-pre-order' );
			$this->template_html = 'emails/pre-order-date-end.php';

			add_action( 'yith_ywpo_sale_date_end', array( $this, 'trigger' ) );

			parent::__construct();

			$this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
			$this->email_type = 'html';
		}

		public function trigger( $preorder_products ) {
			if ( ! $this->is_enabled() ) {
				return;
			}
			$this->object     = $preorder_products;
			$this->email_body = $this->get_option( 'email_body', __( 'Hi Admin, these Pre-Order products are about to expire:', 'yith-woocommerce-pre-order' ) );

			$this->send( $this->get_recipient(),
				$this->get_subject(),
				$this->get_content(),
				$this->get_headers(),
				$this->get_attachments() );
		}

		public function get_content_html() {
			return wc_get_template_html( $this->template_html, array(
				'email_heading' => $this->get_heading(),
				'sent_to_admin' => true,
				'plain_text'    => false,
				'email'         => $this
			),
				'',
				YITH_WCPO_TEMPLATE_PATH );
		}

		public function init_form_fields() {
			$this->form_fields = array(
				'enabled'    => array(
					'title'   => __( 'Enable/Disable', 'yith-woocommerce-pre-order' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable this email notification', 'yith-woocommerce-pre-order' ),
					'default' => 'yes'
				),
				'recipient' => array(
					'title'         => __( 'Recipient(s)', 'yith-woocommerce-pre-order' ),
					'type'          => 'text',
					'description'   => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to %s.', 'yith-woocommerce-pre-order' ), '<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>' ),
					'placeholder'   => '',
					'default'       => '',
					'desc_tip'      => true,
				),
				'subject'    => array(
					'title'       => __( 'Subject', 'yith-woocommerce-pre-order' ),
					'type'        => 'text',
					'description' => sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'yith-woocommerce-pre-order' ), $this->subject ),
					'placeholder' => '',
					'default'     => $this->subject,
					'desc_tip'    => true
				),
				'heading'    => array(
					'title'       => __( 'Email Heading', 'yith-woocommerce-pre-order' ),
					'type'        => 'text',
					'description' => sprintf( __( 'This controls the main heading included in the email notification. Leave blank to use the default heading: <code>%s</code>.', 'yith-woocommerce-pre-order' ), $this->heading ),
					'placeholder' => '',
					'default'     => $this->heading,
					'desc_tip'    => true
				),
				'email_body' => array(
					'title'       => __( 'Email Body', 'yith-woocommerce-pre-order' ),
					'type'        => 'textarea',
					'description' => sprintf( __( 'Defaults to <code>%s</code>', 'yith-woocommerce-pre-order' ), $this->email_body ),
					'placeholder' => '',
					'default'     => $this->email_body,
				)
			);
		}

	}

}
return new YITH_Pre_Order_Date_End_Email();
