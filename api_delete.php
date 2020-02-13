<?php

    function api_delete()
    {
        
        if(isset($_POST['api-submit-del']))
        {
            $user      = wp_get_current_user();
            $urldel    = site_url().'/wp-json/wp/v2/posts/'.$_POST['id'];
            $reqdel    = wp_remote_request($urldel, [
                    'headers' => array(
                        'Authorization' => 'Basic ' . base64_encode($user->user_login.':'.$_POST['pass']),
                    ),
                    'method' => 'DELETE'
                ]);
            wp_redirect(admin_url());
        }
        if(isset($_GET['id']))
        {
            ?>
                <form action="<?php esc_url( $_SERVER['REQUEST_URI'] )?>" method="POST">
                <?php
                    if(isset($_GET['id']))
                    {
                        echo '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
                    }
                ?>
                <p>
                    Password <br>
                    <input type="password" name="pass" required>
                </p>
                <p>
                    <input type="submit" name="api-submit-del" value="Send">
                </p>
            </form>
            <?php
        }else{
            echo 'Belum pilih data!';
        }
    }

?>