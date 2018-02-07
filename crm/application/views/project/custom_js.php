<script type="text/javascript">
    $('#<?=$form_name?>').formValidation({
		framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
			project_name: {
                validators: {
                    notEmpty: {
                        message: 'Please enter the project name.'
                    },
                }
            },
			start_date: {
				validators: {
					date: {
						message: 'The start date is not valid',
						format: 'YYYY-MM-DD',
						max: 'end_date'
					}
				}
			},
			end_date: {
				validators: {
					date: {
						message: 'The end date is not valid',
						format: 'YYYY-MM-DD',
						min: 'start_date'
					}
				}
			}
			
        }
    });
    $('.input-group.date').datepicker({
        format: "yyyy-mm-dd",
        orientation: "bottom auto",
        // daysOfWeekDisabled: "0,5",
        //endDate: '<?= date('Y-m-d') ?>',
        autoclose: true,
        todayHighlight: true
    })
	.on('changeDate', function(e) {
            // Revalidate the date field
            $(this).formValidation('revalidateField', 'start_date');
            $(this).formValidation('revalidateField', 'end_date');
			//$("#submitButton").remove('disabled', 'disabled');
        });
	
	$( "#user_allocated").keyup(function() {
		calculate_productivity_required();
	});
	$( "#productivity_required").keyup(function() {
		calculate_productivity_required();
	});
	$( "#end_date").keyup(function() {
		calculate_productivity_required();
	});
	function calculate_productivity_required(){
		var users_alloc = $('#user_allocated').val();
		var project_req = $('#productivity_required').val();
		var per_day_required= (Math.ceil(project_req/users_alloc));
		$("#per_day_required").val(per_day_required);
		
		/*var startDate = $("#start_date").val();
		var endDate = $("#end_date").val();

		if ((Date.parse(startDate) > Date.parse(endDate))) {
			alert("End date should be greater than Start date");
			$("#end_date").val('');
		}*/
	};
	// Start the function for adding the more boxes Purchasing Time
	$(document).ready(function(){
		var maxField_pt = 8; //Input fields increment limitation
		var addButton_pt = $('.add_button_pt'); //Add button selector
		var wrapper_pt = $('.field_wrapper_pt'); //Input field wrapper
		var fieldHTML_pt = '<div class="row remove_field_wrapper_pt"><div class="form-group col-xs-12 col-md-3"><input class="form-control" name="interval_from[]" value="" id="interval_from[]" min="1" max="1000" type="number" required="required"></div><div class="form-group col-xs-12 col-md-3"><input class="form-control" name="interval_to[]" value="" id="interval_to[]" min="1" max="1000" type="number" required="required"></div><div class="form-group col-xs-12 col-md-3"><select name="interval_type[]" id="interval_type[]" class="form-control" required="required"><option value="" selected="selected">--Select--</option><option value="1">Day(s)</option><option value="2">Month(s)</option></select></div><div class="form-group col-xs-12 col-md-3"><a href="javascript:void(0);" class="remove_button_pt" title="Remove field"><i class="fa  fa-remove "></i></a></div></div>'; //New input field html 
		
		var x_pt = 1; //Initial field counter is 1
		$(addButton_pt).click(function(){ //Once add button is clicked
			if(x_pt < maxField_pt){ //Check maximum number of input fields
				x_pt++; //Increment field counter
				$(wrapper_pt).append(fieldHTML_pt); // Add field html
			}
		});
		$(wrapper_pt).on('click', '.remove_button_pt', function(e){ //Once remove button is clicked
			e.preventDefault();
			$(this).closest('.remove_field_wrapper_pt').remove();
			//$(this).parent('remove').remove(); //Remove field html
			x_pt--; //Decrement field counter
		});
	});
	// Start the function for adding the more boxes Requirement Criteria
	$(document).ready(function(){
		var maxField_rc = 8; //Input fields increment limitation
		var addButton_rc = $('.add_button_rc'); //Add button selector
		var wrapper_rc = $('.field_wrapper_rc'); //Input field wrapper
		var fieldHTML_rc = '<div class="row remove_field_wrapper_rc"><div class="form-group col-xs-12 col-md-3"><select name="input_type[]" id="input_type[]" class="form-control" required="required"><option value="1">Text Box</option><option value="2">Check Box</option><option value="3">Radio Button</option><option value="4">Select Box</option><option value="5">Text Area</option></select></div><div class="form-group col-xs-12 col-md-3"><input class="form-control" name="input_label[]" value="" id="input_label[]" maxlength="100" type="text" required="required"></div><div class="form-group col-xs-12 col-md-3"><input class="form-control" name="input_value[]" value="" id="input_value[]" maxlength="100" type="text" required="required"></div><div class="form-group col-xs-12 col-md-3"><a href="javascript:void(0);" class="remove_button_rc" title="Remove field"><i class="fa  fa-remove "></i></a></div></div>'; //New input field html 
		
		var x_rc = 1; //Initial field counter is 1
		$(addButton_rc).click(function(){ //Once add button is clicked
			if(x_rc < maxField_rc){ //Check maximum number of input fields
				x_rc++; //Increment field counter
				$(wrapper_rc).append(fieldHTML_rc); // Add field html
			}
		});
		$(wrapper_rc).on('click', '.remove_button_rc', function(e){ //Once remove button is clicked
			e.preventDefault();
			$(this).closest('.remove_field_wrapper_rc').remove();
			//$(this).parent('remove').remove(); //Remove field html
			x_rc--; //Decrement field counter
		});
	});
	function project_type_showhide(val){
		if(val==1){
			//show
			$('.remove_field_wrapper_pt').remove();
			$('.remove_field_wrapper_rc').remove();
			$("#show_hide_project_type").show();
		}else{
			//hide
			$('.remove_field_wrapper_pt').remove();
			$('.remove_field_wrapper_rc').remove();
			$("#show_hide_project_type").hide();
		}
	}
	function remove_project_time(pt_id){
		if(confirm("Are you sure you want to delete this purchasing time?")){
		$.ajax({
					url: "<?= site_url('project/project/delete_project_time') ?>",
					type: "POST",
					data: {
						'id' : pt_id
					},
					success: function(response) {
						//alert(response);
						if (response == "deleted") {
							$('#project_time_' + pt_id).remove();
							bootbox.alert('The Purchasing Time has been deleted.');
						}
					},
					dataType: "text"
				});
		}		
	}
	function remove_requirement_criteria(rc_id){
		if(confirm("Are you sure you want to delete this requirement criteria?")){
		$.ajax({
					url: "<?= site_url('project/project/delete_requirement_criteria') ?>",
					type: "POST",
					data: {
						'id' : rc_id
					},
					success: function(response) {
						//alert(response);
						if (response == "deleted") {
							$('#requirement_criteria_' + rc_id).remove();
							bootbox.alert('The Requirement Criteria has been deleted.');
						}
					},
					dataType: "text"
				});
		}		
	}
    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
</script>