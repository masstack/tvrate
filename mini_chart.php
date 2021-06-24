            <?php 
//            print_r (get_field('dizajn'));
                $dizajn = get_field('dizajn');
                $obrabotka_dvizheniya = get_field('obrabotka_dvizheniya');
                $kachestvo_izobrazheniya = get_field('kachestvo_izobrazheniya');
                $vhody = get_field('vhody');
                $zvuk = get_field('zvuk');
                $smart_tv = get_field('smart_tv');

                $dizajn_sum = round(array_sum($dizajn) / count($dizajn));
                $kachestvo_izobrazheniya_sum = round(array_sum($kachestvo_izobrazheniya) / count($kachestvo_izobrazheniya));
                $obrabotka_dvizheniya_sum = round(array_sum($obrabotka_dvizheniya) / count($obrabotka_dvizheniya));
                $vhody_sum = round(array_sum($vhody) / count($vhody));
                $zvuk_sum = round(array_sum($zvuk) / count($zvuk));
                $smart_tv_sum = round(array_sum($smart_tv) / count($smart_tv));
                $verdict_sum = round((($dizajn_sum * 0.05) + ($kachestvo_izobrazheniya_sum * 0.35) + ($obrabotka_dvizheniya_sum * 0.20) + ($vhody_sum * 0.20) + ($zvuk_sum * 0.1) + ($smart_tv_sum * 0.1)));

                $kachestvo_izobrazheniya_parrent = "kachestvo_izobrazheniya";
                $kachestvo_izobrazheniya_field = get_field_object('kachestvo_izobrazheniya');

                update_field( 'verdikt_dizajn', $dizajn_sum, $post->ID );
                update_field( 'verdikt_kachestvo_izobrazheniya', $kachestvo_izobrazheniya_sum, $post->ID );
                update_field( 'verdikt_obrabotka_dvizheniya', $obrabotka_dvizheniya_sum, $post->ID );
                update_field( 'verdikt_vhody', $vhody_sum, $post->ID );
                update_field( 'verdikt_zvuk', $zvuk_sum, $post->ID );
                update_field( 'verdikt_smart_tv', $smart_tv_sum, $post->ID );

                $editor_score = get_post_meta((int)$post->ID, 'rehub_review_editor_score', true);
                $tvrate_score = $verdict_sum / 10;

                update_post_meta($post->ID, 'tvrate_score', $verdict_sum / 10);
                update_post_meta($post->ID, 'rehub_review_overall_score', round(($tvrate_score + $editor_score) / 2, 1));

                $dizajn_criterias = 'Качество Сборки: ' . get_field("dizajn_kachestvo_sborki") . '%';
                $kachestvo_izobrazheniya_criterias = 
                    array(
                        'Контраст' => get_field("kachestvo_izobrazheniya_kontrast"),
                        'Яркость SDR' => get_field("kachestvo_izobrazheniya_yarkost_sdr"),
                    );
                    //print_r($kachestvo_izobrazheniya_field);
            ?>
<div class="position-relative">
    <div class="mini-chart">
                <div class="chart-container" style="position: relative; height:250px; width:300px; overflow: hidden; padding: 15px;">
                    <canvas id="mini-chart" style="position: absolute; top: -25px; overflow: hidden; display: block;" width="300" height="300" class="chartjs-render-monitor"></canvas>
                </div>
                <script>
                  Chart.pluginService.register({
                    beforeDraw: function(chart) {
                      var width = chart.chart.width,
                          height = chart.chart.height,
                          ctx = chart.chart.ctx;

                      ctx.restore();
                      var fontSize = (height / 114).toFixed(2);
                      ctx.font = fontSize + "em sans-serif";
                      ctx.textBaseline = "middle";

                      var text = "<?=$verdict_sum?>%",
                          textX = Math.round((width - ctx.measureText(text).width) / 2),
                          textY = height / 2;

                      ctx.fillText(text, textX, textY);
                      ctx.fillStyle = '#000';
                      ctx.save();
                    }
                  });
                    var minictx = document.getElementById('mini-chart').getContext('2d');
                    var footerLine1 = [
                        '<?=$dizajn_criterias; ?>',
                        '<?='Контраст: ' . get_field("kachestvo_izobrazheniya_kontrast") . '%'?>',
                        '<?='Время отклика: ' . get_field("obrabotka_dvizheniya_vremya_otklika") . '%'?>',
                        '<?='Заддержка ввода: ' . get_field("vhody_zaderzhka_vvoda") . '%'?>',
                        '<?='Частотный отклик (АЧХ): ' . get_field("zvuk_chastotnyj_otklik") . '%'?>',
                        '<?='Интерфейс: ' . get_field("smart_tv_interfejs") . '%'?>'
                    ];
                    var footerLine2 = [
                        '',
                        '<?='Яркость SDR/HDR: ' . get_field("kachestvo_izobrazheniya_yarkost_sdr") . '%' . '/'.get_field("kachestvo_izobrazheniya_yarkost_hdr") . '%'?>',
                        '<?='Вставка черного кадра: ' . get_field("obrabotka_dvizheniya_vstavka_chernogo_kadra") . '%'?>',
                        '<?='Поддерживаемые разрешения: ' . get_field("vhody_podderzhivaemye_razresheniya") . '%'?>',
                        '<?='Искажение: ' . get_field("zvuk_iskazhenie") . '%'?>',
                        '<?='Реклама: ' . get_field("smart_tv_reklama") . '%'?>'
                    ];
                    var footerLine3 = [
                        '',
                        '<?='Однородность серого/черного: ' . get_field("kachestvo_izobrazheniya_odnorodnost_serogo") . '%' . '/'. get_field("kachestvo_izobrazheniya_odnorodnost_chernogo") . '%'?>',
                        '<?='Заикание: ' . get_field("obrabotka_dvizheniya_zaikanie") . '%'?>',
                        '<?='Порты: ' . get_field("vhody_porty") . '%'?>',
                        '',
                        '<?='Приложение: ' . get_field("smart_tv_prilozheniya") . '%'?>'
                    ];
                    var footerLine4 = [
                        '',
                        '<?='Локальное затемнение: ' . get_field("kachestvo_izobrazheniya_lokalnoe_zatemnenie"). '%'?>',
                        '<?='Дрожание 24p: ' . get_field("obrabotka_dvizheniya_drozhanie_24p") . '%'?>',
                        '',
                        '',
                        '<?='Пульт ДУ: ' . get_field("smart_tv_pult_du") . '%'?>'
                    ];
                    var footerLine5 = [
                        '',
                        '<?='Углы обзора: ' . get_field("kachestvo_izobrazheniya_ugol_obzora"). '%'?>',
                        '<?='Переменная частота обновления (VRR): ' . get_field("obrabotka_dvizheniya_peremennaya_chastota_obnovleniya_vrr") . '%'?>',
                        '',
                        '',
                        ''
                    ];

                  var chart = new Chart(minictx, {
                      // The type of chart we want to create
                      type: 'radar',

                      // The data for our dataset
                      data: {
                          labels: ['Дизайн', ['Качество', 'Изображение'], ['Обработка','Движения'], 'Входы', 'Звук', 'Smart TV'],
                          datasets: [
                            {
                                label: '<?php the_title(); ?>',
                                data: [<?=$dizajn_sum?>, <?=$kachestvo_izobrazheniya_sum?>,<?=$obrabotka_dvizheniya_sum?>, <?=$vhody_sum?>, <?=$zvuk_sum?>, <?=$smart_tv_sum?>],
                                fill: true,
                                backgroundColor: 'rgb(35, 211, 211, 0.5)',
                                borderColor: 'rgb(35, 211, 211)'
                            },
                          ]
                      },

                      // Configuration options go here
                      options: {
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var label = ' ';
                                    label += Math.round(tooltipItem.yLabel * 100) / 100;
                                    return label + '%';
                                },
                                beforeFooter: function(tooltipItems, data) { 
                                  return [footerLine1[tooltipItems[0].index], footerLine2[tooltipItems[0].index], footerLine3[tooltipItems[0].index], footerLine4[tooltipItems[0].index], footerLine5[tooltipItems[0].index]];
                                }
                            }
                        },
                        scale: {
                            r: {
                              angleLines: {
                                  display: false
                              },
                              suggestedMin: 0,
                              suggestedMax: 100
                            },
                            ticks: {
                                suggestedMin: 0,
                                suggestedMax: 100,
                                callback: function(value, index, values) {
                                    return '';
                                },
                                display: false
                            },
                            pointLabels: {
                              fontSize: 10,
                              fontColor: 'rgb(38, 45, 63)'
                            }
                        },
                        legend: {
                            display: false,
                              labels: {
                                display: false
                              }
                          }
                      }
                  });
                </script>
              </div>
</div>