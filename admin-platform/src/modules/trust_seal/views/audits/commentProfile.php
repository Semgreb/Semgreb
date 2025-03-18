<div class="media">
    <div class="media-left media-top ">
        <?php echo $profileComments;  ?>
    </div>
    <div class="media-body">
        <h4 class="media-heading">
            <?php echo  "<strong>$name_staff</strong>   <small style='opacity: 0.7;'>" . date('F d, Y', strtotime(substr($date, 0, 10))) . "</small>"; ?>
        </h4>

        <div class="row">
            <div class="col-md-10">
                <?php echo $comment; ?>
            </div>
        </div>
    </div>
</div>