<table class="wp-list-table widefat">
    <thead>
        <tr>
            <th>Question</th>
            <th>Useful</th>
            <th>Not Useful</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($posts as $post) {
            $useful = intval(get_post_meta($post->ID, 'faqwd_useful', true));
            $none_useful = intval(get_post_meta($post->ID, 'faqwd_non_useful', true));
            $view = intval(get_post_meta($post->ID, 'faqwd_hits', true));
            ?>
            <tr>
                <td><?php echo $post->post_title; ?></td>            
                <td><?php echo $useful; ?></td>            
                <td><?php echo $none_useful; ?></td>            
                <td><?php echo $view; ?></td>            
            </tr>
        <?php } ?>
    </tbody>
</table>