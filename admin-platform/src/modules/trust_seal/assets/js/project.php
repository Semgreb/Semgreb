<script>
     let row = $('#tab_project').find('.row:eq(0)');
     let column = row.find('.col-md-6:eq(0)');
     let drodown = column.find('.selectpicker');
     drodown.find('option:eq(1)').prop('selected', true);
     column.hide();
     $('#tab_project').find('#project_cost').removeClass('hide');
</script>