<script type="text/javascript">

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    var data = <?= $company_months ?>;
    var dates = [];
    var totals = [];

    for (var i in data) {
        // convert month number to month name using moment js lib
        var new_date = moment(data[i].month, 'MM').format('MMMM');
        // push date info to array
        dates.push(new_date + ' ' + data[i].year);
        // push total number to array
        totals.push(data[i].total);
    }

    var barGraph = new Chart(document.getElementById("company_months"), {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [{
                label: 'Total',
                backgroundColor: 'rgba(0, 166, 90, 0.75)',
                borderColor: 'rgba(0, 166, 90, 0.75)',
                hoverBackgroundColor: 'rgba(1, 142, 77, 1)',
                hoverBorderColor: 'rgba(1, 142, 77, 1)',
                data: totals
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        stepSize: 1,
                        beginAtZero: true,
                    }
                }]
            },
            legend: {
                display: false,
            }
        }
    });

</script>