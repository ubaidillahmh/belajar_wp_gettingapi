<?php

    function api_formupdate()
    {
        $data   = []; 
        $url    = 'http://localhost/get_api/wp-json/wp/v2/posts/'.$_GET['id'];
        $req    = wp_remote_get($url);
        if(is_array($req)){
            $data = $req['body'];
            $data = json_decode($data);
        }
        // var_dump($data);die;
        ?>
            <form action="<?php esc_url( $_SERVER['REQUEST_URI'] )?>" method="POST">
                <?php
                    if(isset($_GET['id']))
                    {
                        echo '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
                    }
                ?>
                <p>
                    Title <br>
                    <input type="text" name="title" value="<?php echo $data->title->rendered ?>">
                </p>
                <p>
                    Content <br>
                    <textarea name="content" rows="3"><?php echo $data->content->rendered ?></textarea>
                </p>
                <p>
                    <input type="submit" name="api-submit" value="Send">
                </p>
            </form>
        <?php
    }

    function api_updatedata()
    {
        if(isset($_POST['api-submit']))
        {
            $title      = sanitize_text_field( $_POST['title'] );
            $content    = esc_textarea( $_POST['content'] );
            // echo $title;
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

            // Basic Auth Required
            $url = 'http://localhost/get_api/wp-json/wp/v2/posts/'.$_GET['id'];
            $req = wp_remote_post($url, array(
                'headers' => array(
                    'Authorization' => 'Basic ' . base64_encode('admin:admin'),
                ),
                'method'    => 'POST',
                'body'      => $data
            ));

            if ( is_wp_error( $req ) ) {
                $error_message = $req->get_error_message();
                echo "Something went wrong: $error_message";
             } else {
                // echo json_encode($req);
                echo 'Berhasil Simpan!';
             }
        }
    }

    function api_updatemerge()
    {
        api_updatedata();
        api_formupdate(); 
    }

?>