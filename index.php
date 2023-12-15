<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="./dist/img/favicon-logo.png">
  <link rel="shortcut icon" href="./dist/img/favicon-logo.png" type="image/x-icon">
  <meta property="og:url" content="Door Lock System">
  <meta property="og:title" content="Door Lock System">
  <meta property="og:site_name" content="Door Lock System">

  <!-- CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" type="text/css" href="./dist/css/styles.css">

  <!-- jQuery and jQuery UI 1.11.4 -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <!-- Custom JS -->
  <script src="./dist/js/script.js"></script>

<?php
    $defaultPage = "portal";
    $requestedPage = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : $defaultPage;
    $requestedEvent = isset($_GET['event']) ? htmlspecialchars($_GET['event']) : $defaultPage;

    $pageEvent = "event/" . $requestedEvent . ".php";
    $pageDashboard = "page/dashboard/" . $requestedPage . ".php";
    $pagePath = "page/" . $requestedPage . ".php";

    if (file_exists($pageEvent)) {
        require $pageEvent;
    } elseif (file_exists($pageDashboard)) {
        require $pageDashboard;
    } elseif (file_exists($pagePath)) {
        require $pagePath;
    } else {
        require "./page/error/404.php";
    }
?>
  <!-- Table JS -->
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script><!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script><!-- DataTables Bootstrap 4 JS -->
  <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script><!-- DataTables Responsive JS -->
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script><!-- DataTables Buttons JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script><!-- PDFMake -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script><!-- PDFMake -->
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script><!-- DataTables Buttons HTML5 JS -->
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script><!-- DataTables Buttons Print JS -->
  
  <!-- ChartJS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jquery.vmap.min.js"></script><!-- JQVMap -->
  <script src="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/maps/jquery.vmap.usa.js"></script><!-- JQVMap -->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@1.13.0/js/jquery.overlayScrollbars.min.js"></script><!-- overlayScrollbars -->
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/js/adminlte.min.js"></script><!-- AdminLTE App -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script><!-- Bootstrap 4 -->

  <script>
    $(document).ready(function() {
      $("#dataManagementTable").DataTable({
        "lengthChange": true,
      }).container().appendTo('#dataManagementTable_wrapper .col-md-6:eq(0)');
    });

    $(document).ready(function() {
      $("#dataManagementTableVer").DataTable({
        "lengthChange": false,
        "buttons": [
          { extend: 'copy', text: 'Copy to Clipboard', className: 'btn btn-default'},
          { extend: 'csv', text: 'Export to CSV', className: 'btn btn-default'},
          { extend: 'print', text: 'Export to PDF', className: 'btn btn-default'}
        ]
      }).buttons().container().appendTo('#dataManagementTableVer_wrapper .col-md-6:eq(0)')
      .addClass('btn-group');
    });
  </script>
<script>
    var jsonDataCard = <?php echo json_encode($dataTappingcard); ?>;
    var jsonDataGuest = <?php echo json_encode($dataGuest); ?>;
    var jsonDataButton = <?php echo json_encode($dataButton); ?>;

    var labels = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    var datasetsCard = processData(jsonDataCard);
    var ctxCard = document.getElementById('chartTapping').getContext('2d');
    var chartCard = new Chart(ctxCard, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasetsCard
        },
        options: {
          scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Day of Week'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Actions'
                    }
                }
            }
        }
    });


    var datasetsGuest = processData(jsonDataGuest);
    var ctxGuest = document.getElementById('chartGuest').getContext('2d');
    var chartGuest = new Chart(ctxGuest, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasetsGuest
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Day of Week'
                    },
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Actions'
                    }
                }
            }
        }
    });

    var datasetsButton = processData(jsonDataButton);
    var ctxButton = document.getElementById('chartButton').getContext('2d');
    var chartButton = new Chart(ctxButton, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasetsButton
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Day of Week'
                    },
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Actions'
                    }
                }
            }
        }
    });

    function processData(data) {
        var datasets = [];
        var ownersData = {};

        data.forEach(function (entry) {
            var owner = entry.owners;
            if (!ownersData[owner]) {
                ownersData[owner] = {
                    label: owner,
                    data: Array(labels.length).fill(0),
                    backgroundColor: 'rgba(' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ',0.5)'
                };
            }
            ownersData[owner].data[parseInt(entry.day_of_week) - 1] += parseInt(entry.total_actions);
        });

        Object.keys(ownersData).forEach(function (owner) {
            datasets.push(ownersData[owner]);
        });

        return datasets;
    }
</script>

</body>
</html>
