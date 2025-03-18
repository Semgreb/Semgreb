<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends AdminController
{
    private $color = array(
        '#6495ED', '#40E0D0', '#FF7F50', '#FFBF00', '#DE3163', '#9FE2BF', '#7B68EE', '#2E8B57', '#66CDAA', '#7FFFD4',
        '#48D1CC', '#B0C4DE', '#00BFFF', '#4169E1', '#CD853F', '#708090', '#5F9EA0', '#AFEEEE', '#00FFFF', '#008080'
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reports_model');
    }

    public function process_certifications($reporte = '', $tipo_filtros = '', $fecha_desde = '', $fecha_hasta = '')
    {

        if ($this->input->is_ajax_request()) {
            $this->load->library('REPORT_CONSTANT');
            $label_report = '';
            $label_title = '';

            foreach (get_reports_certifications() as $status) {
                if (sprintf('report_certification%s', $status['code']) == $reporte) {
                    $label_report = $status['translate_name'];
                    break;
                }
            }

            if ($label_report == '') {
                foreach (get_reports_audits() as $status) {
                    if (sprintf('report_audit%s', $status['code']) == $reporte) {
                        $label_report = $status['translate_name'];
                        break;
                    }
                }
            }

            $label_title =  _l($reporte . "_title");

            $data['label_report'] = $label_report;
            $data['label_report_title'] =  $label_title;

            $chart = [
                'labels'   => [],
                'datasets' => [
                    [
                        'label'           => [],
                        'backgroundColor' => [],
                        //   'backgroundColor' => 'rgba(197, 61, 169, 0.5)',
                        //  'borderColor'     => '#c53da9',
                        'borderWidth'     => 1,
                        'data'            => [],
                        'hoverBackgroundColor' => []
                    ],
                ],
            ];

            switch ($reporte) {
                case REPORT_CONSTANT::INDICE_CERTIFICACIONES_OTORGADAS_POR_PERIODO:
                    $extraWhere = $this->get_where_report_period($tipo_filtros,  $fecha_desde,  $fecha_hasta);
                    $result = $this->Reports_model->indice_certificaciones_por_periodos($extraWhere);

                    $month_list = [];

                    for ($a = 1; $a < 13; $a++) {
                        $monthsc = _l(date('F', mktime(0, 0, 0, (int)substr($a, 0, 2), 1)));
                        $month_list[] =    $monthsc;
                        $val[$monthsc] = 0;
                    }

                    $chart['labels'] =  $month_list;


                    $i = 0;
                    foreach ($result as $info) {
                        //$chart['labels'][$i] = _l(date('F', mktime(0, 0, 0, (int)substr($info['production_month'], 0, 2), 1)));
                        // $chart['datasets'][0]['label'][$i] = $this->color[$i];
                        $chart['datasets'][$i]['backgroundColor'] = $this->color[$i];
                        $chart['datasets'][$i]['hoverBackgroundColor'] = adjust_color_brightness($this->color[$i], -20);



                        $currentIndex = ((int)substr($info['production_month'], 0, 2) - 1);


                        if ($val[$month_list[$currentIndex]] == 0) {
                            $val[$month_list[$currentIndex]] =  $info['count'];
                        } else {
                            $val[$month_list[$currentIndex]] +=  $info['count'];
                        }

                        $chart['datasets'][$i]['data'] = $val;
                        $val[$month_list[$currentIndex]] = 0;

                        $i++;
                    }

                    $data['_statistics_'] = json_encode($chart);
                    echo  $this->load->view('trust_seal/reports/certifications/indice_certificaciones_por_periodos', $data, true);
                    break;

                case REPORT_CONSTANT::INDICE_CERTIFICACIONES_SELLO:

                    $extraWhere = $this->get_where_report_period($tipo_filtros,  $fecha_desde,  $fecha_hasta, 'ct.date');
                    $result = $this->Reports_model->indice_certificaciones_por_sellos($extraWhere);

                    $i = 0;
                    foreach ($result as $info) {
                        $chart['labels'][$i] =  $info['title'];
                        // $chart['datasets'][0]['label'][$i] = $this->color[$i];
                        $chart['datasets'][0]['backgroundColor'][$i] = $this->color[$i];
                        $chart['datasets'][0]['data'][$i] = $info['count'];
                        $chart['datasets'][0]['hoverBackgroundColor'][$i] = adjust_color_brightness($this->color[$i], -20);

                        $i++;
                    }

                    $data['_statistics_'] = json_encode($chart);
                    echo  $this->load->view('trust_seal/reports/certifications/indice_certificaciones_por_periodos', $data, true);
                    break;

                case REPORT_CONSTANT::INDICE_CERTIFICACIONES_ESTADO:

                    $extraWhere = $this->get_where_report_period($tipo_filtros,  $fecha_desde,  $fecha_hasta, 'ct.date');

                    $extraWhere  = substr($extraWhere, 3);

                    $result = $this->Reports_model->indice_certificaciones_por_estado($extraWhere);

                    $i = 0;
                    foreach ($result as $info) {

                        $status = '';

                        foreach (get_status_certifications() as $qualification) {
                            if ($qualification['status'] == $info['status']) {
                                $status = $qualification['translate_name'];
                                break;
                            }
                        }

                        $chart['labels'][$i] =  $status;
                        // $chart['datasets'][0]['label'][$i] = $this->color[$i];
                        $chart['datasets'][0]['backgroundColor'][$i] = $this->color[$i];
                        $chart['datasets'][0]['data'][$i] = $info['count'];
                        $chart['datasets'][0]['hoverBackgroundColor'][$i] = adjust_color_brightness($this->color[$i], -20);

                        $i++;
                    }

                    $data['_statistics_'] = json_encode($chart);
                    echo  $this->load->view('trust_seal/reports/certifications/indice_certificaciones_por_periodos', $data, true);
                    break;


                case REPORT_CONSTANT::INDICE_TOTAL_AUDITORIAS_CALIFICACION:

                    $extraWhere = $this->get_where_report_period($tipo_filtros,  $fecha_desde,  $fecha_hasta, 'au.date');

                    $extraWhere  = substr($extraWhere, 3);

                    $result = $this->Reports_model->indice_auditorias_por_calificacion($extraWhere);

                    $i = 0;
                    foreach ($result as $info) {

                        $status = '';

                        $qualificationName = "";
                        foreach (get_qualification_audits() as $qualification) {
                            if ($qualification['qualification'] == $info['qualification']) {
                                $qualificationName = $qualification['translate_name'];
                                break;
                            }
                        }

                        $chart['labels'][$i] =  $qualificationName;
                        // $chart['datasets'][0]['label'][$i] = $this->color[$i];
                        $chart['datasets'][0]['backgroundColor'][$i] = $this->color[$i];
                        $chart['datasets'][0]['data'][$i] = $info['count'];
                        $chart['datasets'][0]['hoverBackgroundColor'][$i] = adjust_color_brightness($this->color[$i], -20);

                        $i++;
                    }

                    $data['_statistics_'] = json_encode($chart);
                    echo  $this->load->view('trust_seal/reports/certifications/indice_certificaciones_por_periodos', $data, true);
                    break;

                case REPORT_CONSTANT::INDICE_AUDITORIAS_ESTADO:


                    /***  labels: [
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                ],
                datasets: [{
                    label: 'My First dataset',
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: [0, 10, 5, 2, 20, 30, 45],
                }] */
                    $extraWhere = $this->get_where_report_period($tipo_filtros,  $fecha_desde,  $fecha_hasta, 'au.date');

                    $extraWhere  = substr($extraWhere, 3);

                    $result = $this->Reports_model->indice_auditorias_por_estado($extraWhere);

                    $month_list = [];

                    for ($a = 1; $a < 13; $a++) {
                        $monthsc = _l(date('F', mktime(0, 0, 0, (int)substr($a, 0, 2), 1)));
                        $month_list[] =    $monthsc;
                        $val[$monthsc] = 0;
                    }

                    $chart['labels'] =  $month_list;

                    $i = 0;
                    foreach ($result as $info) {

                        $status = '';

                        foreach (get_status_audits() as $qualification) {
                            if ($qualification['status'] == $info['status']) {
                                $status = $qualification['translate_name'];
                                break;
                            }
                        }


                        $chart['datasets'][$i]['label'] = $status;
                        $chart['datasets'][$i]['borderColor'] = $this->color[$i];
                        $chart['datasets'][$i]['backgroundColor'] = $this->color[$i];

                        $currentIndex = ((int)substr($info['production_month'], 0, 2) - 1);


                        if ($val[$month_list[$currentIndex]] == 0) {
                            $val[$month_list[$currentIndex]] =  $info['count'];
                        } else {
                            $val[$month_list[$currentIndex]] +=  $info['count'];
                        }

                        $chart['datasets'][$i]['data'] = $val;
                        $val[$month_list[$currentIndex]] = 0;

                        unset($chart['datasets'][0]['borderWidth']);
                        unset($chart['datasets'][0]['hoverBackgroundColor']);

                        //adjust_color_brightness($this->color[$i], -20);
                        // echo $data[$currentIndex];
                        // die();
                        // die($currentIndex);
                        $i++;
                    }


                    $data['_statistics_'] = json_encode($chart);


                    // echo "<pre>";
                    // print_r($chart);
                    // die();


                    echo  $this->load->view('trust_seal/reports/certifications/indice_line', $data, true);
                    break;


                case REPORT_CONSTANT::INDICE_TOTAL_AUDITORIAS_COMPLETADAS:

                    $extraWhere = $this->get_where_report_period($tipo_filtros,  $fecha_desde,  $fecha_hasta, 'au.date');
                    $result = $this->Reports_model->indice_auditoria_completadas($extraWhere);



                    $month_list = [];

                    for ($a = 1; $a < 13; $a++) {
                        $monthsc = _l(date('F', mktime(0, 0, 0, (int)substr($a, 0, 2), 1)));
                        $month_list[] =    $monthsc;
                        $val[$monthsc] = 0;
                    }

                    $chart['labels'] =  $month_list;

                    $i = 0;
                    foreach ($result as $info) {


                        // $chart['datasets'][0]['label'][$i] = $this->color[$i];
                        $chart['datasets'][$i]['backgroundColor'] = $this->color[$i];

                        $chart['datasets'][$i]['hoverBackgroundColor'] = adjust_color_brightness($this->color[$i], -20);


                        $currentIndex = ((int)substr($info['production_month'], 0, 2) - 1);


                        if ($val[$month_list[$currentIndex]] == 0) {
                            $val[$month_list[$currentIndex]] =  $info['count'];
                        } else {
                            $val[$month_list[$currentIndex]] +=  $info['count'];
                        }


                        $chart['datasets'][$i]['data'] = $val;
                        $val[$month_list[$currentIndex]] = 0;

                        $i++;
                    }

                    $data['_statistics_'] = json_encode($chart);
                    echo  $this->load->view('trust_seal/reports/certifications/indice_certificaciones_por_periodos', $data, true);
                    break;


                    //
            }
        } else {

            $data['certifications_years']  = $this->Reports_model->get_distinct_certifications_years();
            $data['audits_years']  = $this->Reports_model->get_distinct_audits_years();
            $this->app_scripts->add('report-js', module_dir_url('trust_seal', 'assets/js/chart.js'), 'admin', ['app-js']);
            $data['title']   = _l('reports_certification');
            $this->load->view('trust_seal/reports/process_certifications', $data);
        }
    }

    private function get_where_report_period($months_report, $report_from, $report_to, $field = 'date')
    {
        $custom_date_select = '';
        if ($months_report != '') {
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth   = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int) $months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth   = date('Y-m-t');
                }

                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            } elseif ($months_report == 'this_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                    date('Y-m-d', strtotime(date('Y-01-01'))) .
                    '" AND "' .
                    date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                    date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                    '" AND "' .
                    date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($report_from);
                $to_date   = to_sql_date($report_to);
                if ($from_date == $to_date) {
                    $custom_date_select = 'AND ' . $field . ' = "' . $this->db->escape_str($from_date) . '"';
                } else {
                    $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $this->db->escape_str($from_date) . '" AND "' . $this->db->escape_str($to_date) . '")';
                }
            }
        }

        return $custom_date_select;
    }
}
