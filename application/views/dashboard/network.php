<script src="<?= base_url('assets/anychart/anychart_base.min.js') ?>"></script>
<!-- Include the data adapter -->
<script src="<?= base_url('assets/anychart/anychart_data_adapter.min.js') ?>"></script>

<style type="text/css">
    #pieChartNetwork,
    #lineChartNetwork {
        width: 100%;
        height: 500px;
    }
</style>

<div class="row row-cols-1 row-cols-lg-2 g-4">
    <div class="col">
        <div class="card h-100">
            <!-- <img src="..." class="card-img-top" alt="..."> -->
            <div class="card-body">
                <h2 class="card-title">Pie Chart - Network</h2>

                <div id="pieChartNetwork"></div>
            </div>
            <div class="card-footer">
                <small class="text-body-secondary">Grafik berikut berdasarkan aktivitas Jaringan.</small>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100">
            <!-- <img src="..." class="card-img-top" alt="..."> -->
            <div class="card-body">
                <h2 class="card-title">Line Chart - Network</h2>

                <div id="lineChartNetwork"></div>
            </div>
            <div class="card-footer">
                <small class="text-body-secondary">Grafik berikut berdasarkan aktivitas Jaringan.</small>
            </div>
        </div>
    </div>
</div>

<script>
    anychart.onDocumentReady(function() {
        let theme = "<?= $this->input->get("theme") ?>";
        let title = "Tersedianya Infrastruktur Jaringan dan Komunikasi Serta Kelengkapan Office Automation";
        let idElement = "pieChartNetwork";
        let background = {
            fill: {
                keys: ["#fff", "#fff"],
                angle: 90
            },
        };
        let colorCustom = "black";

        if (theme == "dark") {
            background.fill.keys = ["#212529", "#212529"];
            colorCustom = "white";
        }

        let column = anychart.column({
            xScroller: {
                autoHide: true
            },
            yScroller: {
                autoHide: true
            }
        });
        // ----------

        anychart.data.loadJsonFile('<?= site_url('/programkerja?type=Network') ?>', function(data) {
            // create pie chart with passed data
            let pieChart = anychart.pie3d(data);
            pieChart.container(idElement).background(background).title({
                enabled: true,
                text: title,
                hAlign: "center",
                fontColor: colorCustom,
            }).radius("50%").draw(true);
            // ----------

            // line chart
            let idElementB = "lineChartNetwork";
            let lineChart = anychart.line().container(idElementB).background(background).xGrid({
                enabled: true,
                stroke: {
                    color: colorCustom,
                    dash: "3 5"
                },
                palette: [null, "black 0.1"],
            }).yGrid({
                enabled: true,
                stroke: colorCustom
            }).yScale({
                maximum: 110,
                ticks: {
                    interval: 20
                }
            }).xAxis({
                stroke: colorCustom,
                labels: {
                    fontColor: colorCustom,
                    fontWeight: 900,
                    height: 30,
                    vAlign: "middle"
                }
            }).draw(true);
            lineChart.tooltip().titleFormat("{%value}");

            let series = lineChart.line(data).stroke({
                color: "#aaa",
                dash: "4 3",
                thickness: 3
            }).hovered({
                stroke: {
                    color: "#555",
                    dash: "4 3",
                    thickness: 3
                },
                markers: {
                    fill: "darkred",
                    stroke: "2 white"
                }
            }).name("dashed stroke").markers({
                enabled: true,
                fill: "#555",
                stroke: "2 white",
            }).tooltip({
                fontColor: "white",
                background: {
                    fill: "#212529",
                    stroke: colorCustom,
                    cornerType: "round",
                    corners: 4,
                }
            });
            series.tooltip().format(function() {
                let value = data[this.index];
                return value.name;
            });

            let yAxisB = lineChart.yAxis().ticks(null).stroke(colorCustom).minorTicks(null);
            yAxisB.labels().fontColor(colorCustom).fontWeight(900).format("{%value}%");

            let xAxisLabels = lineChart.xAxis().labels();
            xAxisLabels.format(function(param) {
                let value = data[this.index].name;
                return value;
            });
            xAxisLabels.width(65);
            xAxisLabels.rotation(-35);
            xAxisLabels.wordWrap("break-word");
            xAxisLabels.wordBreak("break-all");
            // ----------
        });
    });
</script>