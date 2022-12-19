<?php
$database = new DB();
$db = $database->connect();
$balance = new Balance($db);
$days = json_decode($balance->last_five_days(),true);
$days = array_values($days);
//echo (!empty($balance->total_expenses("$days[9]")) ? $balance->total_expenses("2019-05-01") : 0);
?>
<!-- tasks -->
<div class="agile-last-grids">
    <div class="col-md-6 agile-last-left">
        <div class="agile-last-grid">
            <div class="area-grids-heading">
                <h3>Monthly</h3>
            </div>
            <div id="graph7"></div>
            <script>
                // This crosses a DST boundary in the UK.
                Morris.Area({
                    element: 'graph7',
                    data: [
                        {x: '2013-03-30 22:00:00', y: 3, z: 3},
                        {x: '2013-03-31 00:00:00', y: 2, z: 0},
                        {x: '2013-03-31 02:00:00', y: 0, z: 2},
                        {x: '2013-03-31 04:00:00', y: 4, z: 4}
                    ],
                    xkey: 'x',
                    ykeys: ['y', 'z'],
                    labels: ['Y', 'Z']
                });
            </script>

        </div>
    </div>
    <div class="col-md-6 agile-last-left agile-last-middle">
        <div class="agile-last-grid">
            <div class="area-grids-heading">
                <h3>Daily Expenses, Income and Net Income</h3>
            </div>
            <div id="graph8"></div>
            <script>
                /* data stolen from http://howmanyleft.co.uk/vehicle/jaguar_'e'_type */
                var day_data = [
                    {"period": "<?=$days[9]?>", "expenses": <?php echo (!empty($balance->total_expenses("$days[9]")) ? $balance->total_expenses("$days[9]") : 0); ?>, "income": <?php echo (!empty($balance->total_sales("$days[9]")) ? $balance->total_sales("$days[9]") : 0); ?>,"net":<?php echo $balance->total_sales("$days[9]") - $balance->total_expenses("$days[9]") ?>},
                    {"period": "<?=$days[8]?>", "expenses": <?php echo (!empty($balance->total_expenses("$days[8]")) ? $balance->total_expenses("$days[8]") : 0); ?>, "income": <?php echo (!empty($balance->total_sales("$days[8]")) ? $balance->total_sales("$days[8]") : 0); ?>,"net":<?php echo $balance->total_sales("$days[8]") - $balance->total_expenses("$days[8]") ?>},
                    {"period": "<?=$days[7]?>", "expenses": <?php echo (!empty($balance->total_expenses("$days[7]")) ? $balance->total_expenses("$days[7]") : 0); ?>, "income": <?php echo (!empty($balance->total_sales("$days[7]")) ? $balance->total_sales("$days[7]") : 0); ?>,"net":<?php echo $balance->total_sales("$days[7]") - $balance->total_expenses("$days[7]") ?>},
                    {"period": "<?=$days[6]?>", "expenses": <?php echo (!empty($balance->total_expenses("$days[6]")) ? $balance->total_expenses("$days[6]") : 0); ?>, "income": <?php echo (!empty($balance->total_sales("$days[6]")) ? $balance->total_sales("$days[6]") : 0); ?>,"net":<?php echo $balance->total_sales("$days[6]") - $balance->total_expenses("$days[6]") ?>},
                    {"period": "<?=$days[5]?>", "expenses": <?php echo (!empty($balance->total_expenses("$days[5]")) ? $balance->total_expenses("$days[5]") : 0); ?>, "income": <?php echo (!empty($balance->total_sales("$days[5]")) ? $balance->total_sales("$days[5]") : 0); ?>,"net":<?php echo $balance->total_sales("$days[5]") - $balance->total_expenses("$days[5]") ?>},
                    {"period": "<?=$days[4]?>", "expenses": <?php echo (!empty($balance->total_expenses("$days[4]")) ? $balance->total_expenses("$days[4]") : 0); ?>, "income": <?php echo (!empty($balance->total_sales("$days[4]")) ? $balance->total_sales("$days[4]") : 0); ?>,"net":<?php echo $balance->total_sales("$days[4]") - $balance->total_expenses("$days[4]") ?>},
                    {"period": "<?=$days[3]?>", "expenses": <?php echo (!empty($balance->total_expenses("$days[3]")) ? $balance->total_expenses("$days[3]") : 0); ?>, "income": <?php echo (!empty($balance->total_sales("$days[3]")) ? $balance->total_sales("$days[3]") : 0); ?>,"net":<?php echo $balance->total_sales("$days[3]") - $balance->total_expenses("$days[3]") ?>},
                    {"period": "<?=$days[2]?>", "expenses": <?php echo (!empty($balance->total_expenses("$days[2]")) ? $balance->total_expenses("$days[2]") : 0); ?>, "income": <?php echo (!empty($balance->total_sales("$days[2]")) ? $balance->total_sales("$days[2]") : 0); ?>,"net":<?php echo $balance->total_sales("$days[2]") - $balance->total_expenses("$days[2]") ?>},
                    {"period": "<?=$days[1]?>", "expenses": <?php echo (!empty($balance->total_expenses("$days[1]")) ? $balance->total_expenses("$days[1]") : 0); ?>, "income": <?php echo (!empty($balance->total_sales("$days[1]")) ? $balance->total_sales("$days[1]") : 0); ?>,"net":<?php echo $balance->total_sales("$days[1]") - $balance->total_expenses("$days[1]") ?>},
                    {"period": "<?=$days[0]?>", "expenses": <?php echo (!empty($balance->total_expenses("$days[0]")) ? $balance->total_expenses("$days[0]") : 0); ?>, "income": <?php echo (!empty($balance->total_sales("$days[0]")) ? $balance->total_sales("$days[0]") : 0); ?>,"net":<?php echo $balance->total_sales("$days[0]") - $balance->total_expenses("$days[0]") ?>}
                ];
                Morris.Bar({
                    element: 'graph8',
                    data: day_data,
                    xkey: 'period',
                    ykeys: ['expenses', 'income','net'],
                    labels: ['Expenses', 'Income','Net Income'],
                    xLabelAngle: 60
                });
            </script>
        </div>
    </div>

    <div class="clearfix"> </div>
</div>
<!-- //tasks -->
<script type="text/javascript" src="../js/monthly.js"></script>
<script type="text/javascript">
    $(window).load( function() {

        $('#mycalendar').monthly({
            mode: 'event',

        });

        $('#mycalendar2').monthly({
            mode: 'picker',
            target: '#mytarget',
            setWidth: '250px',
            startHidden: true,
            showTrigger: '#mytarget',
            stylePast: true,
            disablePast: true
        });

        switch(window.location.protocol) {
            case 'http:':
            case 'https:':
                // running on a server, should be good.
                break;
            case 'file:':
                alert('Just a heads-up, events will not work when run locally.');
        }

    });
</script>
<!-- //calendar -->
