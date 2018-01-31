<script type="text/javascript">
    var docDefinition = {
        // footer: function(currentPage, pageCount) { return currentPage.toString() + ' of ' + pageCount; },
        content: [
            { image: '<?= base64_encode_image($this->company_lib->get_company_logo()) ?>', width: 160, alignment: 'center' },
            { text: '<?= $meeting['name'] ?>', style: 'h1', margin: [0, 40, 0, 60] },
            { text: 'Meeting ID Number:', style: 'h4' },
            { text: '#<?= $meeting['id'] ?>', margin: [0, 0, 0, 10] },
            { text: 'Meeting Description:', style: 'h4' },
            { text: '<?= ucfirst($meeting['description']) ?>', margin: [0, 0, 0, 10] },
            { text: 'Added On:', style: 'h4' },
            { text: '<?= date("jS M Y", strtotime($meeting['date'])) ?>', margin: [0, 0, 0, 10] },
            { text: 'Added By:', style: 'h4' },
            { text: '<?= $meeting['profile']['first_name'] ?> <?= $meeting['profile']['last_name'] ?>', margin: [0, 0, 0, 10] },
            { text: 'Attendees', style: 'h4', margin: [0, 10, 0, 10] },
            {
                table: {
                    widths: [300, 'auto'],
                    headerRows: 1,
                    body: [
                        [
                            { text: 'Name', style: 'tableHeader' },
                            { text: 'Status', style: 'tableHeader' }
                        ],
                        <?php foreach ($meeting['attendees'] as $attendee): ?>
                            ['<?= $attendee["profile"]["first_name"] ?> <?= $attendee["profile"]["last_name"] ?>',
                             '<?= ucfirst($attendee["status"]) ?>'],
                        <?php endforeach; ?>
                    ]
                },
                layout: {
                    fillColor: function (i, node) { return (i % 2 === 0) ? '#CCCCCC' : null; }
                }
            },
            { text: 'Agendas', style: 'h4', margin: [0, 20, 0, 10] },
            {
                table: {
                    widths: [300, 'auto', 'auto'],
                    headerRows: 1,
                    body: [
                        [
                            { text: 'Topic', style: 'tableHeader' },
                            { text: 'Presented By', style: 'tableHeader' },
                            { text: 'Allotted Time', style: 'tableHeader' },
                        ],
                        <?php foreach ($meeting['agendas'] as $agenda): ?>
                            ['<?= ucfirst($agenda["topic"]) ?>', '<?= $agenda["profile"]["first_name"] ?> <?= $agenda["profile"]["last_name"] ?>', '<?= $agenda["allotted_time"] ?> mins'],
                        <?php endforeach; ?>
                    ]
                },
                layout: {
                    fillColor: function (i, node) { return (i % 2 === 0) ? '#CCCCCC' : null; }
                }
            },
            { text: 'Actions', style: 'h4', margin: [0, 20, 0, 10] },
            {
                table: {
                    widths: ['auto', 'auto', 'auto', 'auto', 'auto', 'auto'],
                    headerRows: 1,
                    body: [
                        [
                            { text: 'Details', style: 'tableHeader' },
                            { text: 'Close Details', style: 'tableHeader' },
                            { text: 'Assigned To', style: 'tableHeader' },
                            { text: 'Status', style: 'tableHeader' },
                            { text: 'Priority', style: 'tableHeader' },
                            { text: 'Estimated Completion Date', style: 'tableHeader' },
                        ],
                        <?php foreach ($meeting['actions'] as $action): ?>
                            ['<?= $action["details"] ?>',
                             '<?= is_null($action["close_details"]) ? '' : $action["close_details"] ?>',
                             '<?= $action["profile"]["first_name"] ?> <?= $action["profile"]["last_name"] ?>',
                             '<?= ucwords(str_replace("_", " ", $action["status"])) ?>',
                             '<?= ucfirst($action["priority"]) ?>',
                             '<?= date("jS F Y", strtotime($action["ecd"])) ?>'],
                        <?php endforeach; ?>
                    ]
                },
                layout: {
                    fillColor: function (i, node) { return (i % 2 === 0) ? '#CCCCCC' : null; }
                }
            },
            <?php if ($uploads):
                foreach ($uploads as $image):
                    $ext = pathinfo($image['file_name'], PATHINFO_EXTENSION);
                    $image_types = ['gif', 'jpg', 'jpeg', 'png', 'bmp'];
                    $file_url = site_url("uploads/{$this->company['uploads_folder']}/{$image['file_name']}");

                    if (in_array($ext, $image_types))
                    {
                        ?>
                        { image: '<?= base64_encode_image($file_url) ?>', width: 500, margin: [0, 20, 0, 20] },
                        <?php
                    }
                    else
                    {
                        ?>
                        { text: '<?= $file_url ?>', link: '<?= $file_url ?>', style: 'link', margin: [0, 20, 0, 20]},
                        <?php
                    }
                ?>
                <?php endforeach; ?>
            <?php endif; ?>
        ],
        styles: {
            h1: {
                fontSize: 32,
                bold: true,
                alignment: 'center'
            },
            h2: {
                fontSize: 26,
                bold: true,
                alignment: 'center'
            },
            h3: {
                fontSize: 20,
            },
            h4: {
                fontSize: 14,
                bold: true,
            },
            tableHeader: {
                bold: true,
            },
            link: {
                color: 'blue'
            }
        }
    };
</script>