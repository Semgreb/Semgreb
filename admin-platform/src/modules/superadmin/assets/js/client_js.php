<script>
    <?php

    $knowledgebase_url = get_option('superadmin_knowledgebase_link');
    if (!empty($knowledgebase_url)) 
    {
        echo '$("li.customers-nav-item-knowledge-base a").attr("href", "' . $knowledgebase_url . '")';
    }
    ?>
</script>