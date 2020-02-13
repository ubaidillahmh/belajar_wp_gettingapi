<?php

    function api_getdata($loop)
    {
        
        $data   = []; 
        $url    = site_url().'/wp-json/wp/v2/posts';
        $req    = wp_remote_get($url);
        // if(is_array($req)){
        //     $data = $req['body'];
        //     $data = json_decode($data);
        // }
        $data   = json_decode(wp_remote_retrieve_body( $req )); 
        // $total  = count($data);
        ?>
            <div class="table">
                <table border="1px">
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
        <?php

        $i = 0; 
        foreach ($data as $key => $item)
        {
            ?>
                <tr>
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo $item->title->rendered; ?></td>
                    <td><?php echo $item->content->rendered; ?></td>
                    <td><?php echo $item->status; ?></td>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=api_post_update') ?>&update&id=<?php echo $item->id ?>">update</a>
                        <a href="<?php echo admin_url('admin.php?page=api_post_delete') ?>&delete&id=<?php echo $item->id ?>">delete</a>
                    </td>
                </tr>
            <?php

            $i++;
            if($loop != null)
            {
                if($i == $loop)
                {
                    break;
                }
            }
        }
        ?>
            </table>
        </div>
        <?php
    }

    function api_getshortcode($atts, $content=null)
    {
        $atts = array_change_key_case((array)$atts, CASE_LOWER);
    
        $param = shortcode_atts([
                                    'loop' => '',
                                ], $atts);

        ob_start();
        api_getdata($param['loop']);

        return ob_get_clean();
    }

    // contoh input : apabila cuma latest data [api_getshort loop="1"] jika 20 data [api_getshort loop="20"]
    add_shortcode('api_getshort', 'api_getshortcode');

?>