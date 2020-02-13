<?php

    function api_formdata()
    {
        ?>
            <form action="<?php esc_url( $_SERVER['REQUEST_URI'] )?>" method="POST">
                
                <p>
                    Title <br>
                    <input type="text" name="title" value="">
                </p>
                <p>
                    Content <br>
                    <textarea name="content" rows="3"></textarea>
                </p>
                <p>
                    Password <br>
                    <input type="password" name="pass">
                </p>
                <p>
                    <input type="submit" name="api-submit" value="Send">
                </p>
            </form>
        <?php
    }

    function api_postdata()
    {
        if(isset($_POST['api-submit']))
        {
            $title      = sanitize_text_field( $_POST['title'] );
            $content    = esc_textarea( $_POST['content'] );
            $pass       = $_POST['pass'];
            $user       = wp_get_current_user();
            
            $data = array(
                'date'      => date('Y-m-d H:i:s'),
                'date_gmt'      => null,
                'slug'      => $title,
                'status'    => 'publish',
                'password'  => '',
                'title'     => $title,
                'content'   => $content,
                'author'    => 1,
                'excerpt'   => '',
                'featured_media' => null,
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'format'    => 'standard',
                'meta'      => null,
                'sticky'    => true,
                'template'  => '',
                'categories'=> '',
                'tags'      => ''
            );

            // basic auth needed
            $url = site_url().'/wp-json/wp/v2/posts';
            $req = wp_remote_post($url, array(
                'headers' => array(
                    'Authorization' => 'Basic ' . base64_encode($user->user_login.':'.$pass),
                ),
                'method'    => 'POST',
                'body'      => $data
            ));

            if ( is_wp_error( $req ) ) {
                $error_message = $req->get_error_message();
                echo "Something went wrong: $error_message";
             } else {
                echo 'Berhasil Simpan!';
             }
        }
    }

    function api_postmerge()
    {
        api_postdata();
        api_formdata(); 
    }

    function api_postshortcode()
    {
        ob_start();
        api_postdata();
        api_formdata();

        return ob_get_clean();
    }

    add_shortcode('api_postshort', 'api_postshortcode');
?>