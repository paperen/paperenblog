<?php $this->load->module( 'header/common', array( 'archive', $post_data['title'] ) ); ?>
<?php $this->load->module( 'post/common/single', array( $postid_or_urltitle ) ); ?>
<?php $this->load->module( 'sidebar/common', array( ) ); ?>
<?php $this->load->module( 'footer/common', array( ) ); ?>