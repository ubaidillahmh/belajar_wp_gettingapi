<?php

    function api_formupdate()
    {
        $data   = []; 
        $url    = site_url().'/wp-json/wp/v2/posts/'.$_GET['id'];
        $req    = wp_remote_get($url);
        if(is_array($req)){
            $data = $req['body'];
            $data = json_decode($data);
        }
        // echo wp_get_current_user();
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
                    Password <br>
                    <input type="password" name="pass">
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
            $pass       = $_POST['pass'];
            $user       = wp_get_current_user();
            
            $data = array(
                'title'     => $title,
                'content'   => $content,
            );

            // Basic Auth Required
            $url = site_url().'/wp-json/wp/v2/posts/'.$_GET['id'];
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

    function api_updatemerge()
    {
        api_updatedata();
        api_formupdate(); 
    }

?>