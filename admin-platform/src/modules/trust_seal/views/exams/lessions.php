<div style="margin-top:7px;margin-bottom:10px;">
    <button type="button" class="btn btn-secondary" onclick="addLession()"><?php echo _l('new_lession'); ?></button>
</div>
<div class="row" id="listLessions">

    <?php foreach ($lessions as $item) { ?>

        <div class="col-md-12" id="lession_1">
            <div class="panel_s">
                <div class="panel-body" style="background:#f1f5f9;">
                    <div style="display:flex;align-items: center;">
                        <div style="width:90%;">
                            <input type="text" value="<?php echo $item['description']; ?>" class="form-control" id="lession_description_1">
                        </div>
                        <div style="margin-left:5px;">
                            <a class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                            </a>
                            <a class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                <i class="fa-regular fa-trash-can fa-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>
</div>
<?php init_tail(); ?>
<script>
// $(function() {

// });

function add_lession(id, id_lession) {
    let url = '<?php echo admin_url('exams/lession'); ?>';
    let data = {
        'id_section':id_lession,
        'description':document.getElementById('lession_description_'+id).value
    };

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function(res) {
            // console.log(res.success)
            alert_float("success", "added successfully");
        },
        error: function(res) {
            alert_float("danger", "added successfully");
        }
    });

};

let listLessions = document.getElementById('listLessions');
let template = listLessions.innerHTML;
let counter = <?php echo count($lessions) ?> == 0 ? 1 : <?php echo count($lessions) ?> ;
if(<?php echo count($lessions) ?>==0){
    listLessions.innerHTML += template;
}

function addLession(){
    counter += 1;
    template += `
    <div class="col-md-12" id="lession_${counter}">
        <div class="panel_s">
            <div class="panel-body" style="background:#f1f5f9;">
                <div style="display:flex;align-items: center;">
                    <div style="width:90%;">
                        <input type="text" class="form-control" name="" id="lession_description_${counter}">
                    </div>
                    <div style="margin-left:5px;">
                        <button type="submit" class="btn btn-primary pull-right" id="btn_lession_${counter}" onclick="add_lession(${counter},<?php echo $section->id; ?>)"><?php echo _l('submit'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;
    listLessions.innerHTML = template;
}

</script>