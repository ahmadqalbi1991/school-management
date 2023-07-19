$(document).ready(function() {
    $('#scr-vtr-dynamic').DataTable({
        scrollY: '50vh',
        scrollCollapse: true,
        paging: false,
        ordering: false
    });

    $('#strand_id').on('change', function () {
        $.ajax({
            url: '/get-sub-strands/' + $(this).val(),
            type: 'GET',
            success: function (response) {
                $('#sub-strand-div').show();
                $('#sub-strands').html(response).select2();
            }
        })
    });

    $('#sub-strands').on('change', function () {
        $.ajax({
            url: '/get-learning-activities/' + $(this).val(),
            type: 'GET',
            success: function (response) {
                $('#learning-activity-div').show();
                $('#learning-activity').html(response).select2();
            }
        })
    });

    $('#learning-activity').on('change', function () {
        if ($(this).val()) {
            let data = {
                subject_id: $('#subject_id').val(),
                class_id: $('#class_id').val(),
                stream_id: $('#stream_id').val(),
                strand_id: $('#strand_id').val(),
                sub_strand_id: $('#sub-strands').val(),
                term_id: $('#term_id').val(),
                learning_activity_id: $(this).val()
            }

            $.ajax({
                url: '/get-assessments',
                type: 'POST',
                data: {data},
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    let checkboxes = $('.assessment-checkboxes')
                    if (response) {
                        checkboxes.each((index, elem) => {
                            elem = $(elem)
                            if(response[elem.attr('data-learner-id')] == elem.val()) {
                                elem.prop('checked', true)
                            }
                        })
                    }

                    checkboxes.prop('disabled', false)
                    $('#save-btn').prop('disabled', false)
                }
            })
        } else {
            $('.assessment-checkboxes').prop('disabled', true)
            $('#save-btn').prop('disabled', true)
        }
    });
})
