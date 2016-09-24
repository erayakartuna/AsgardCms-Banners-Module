<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Draw  Class
 *
 * @author      Eray Akartuna
 * @package 	Beework Cms
 * @subpackage  library
 */
class Draw{

    var $city_id = 0;//Kura yapılan il id si
    var $current_time = 0; //saatimizi tutan değişken
    var $template_name = 'sablon.xlsx'; //templatein ismi
    var $current_col = 8; //kaçıncı satırdan başlayacağını belirliyoruz
    var $timeout_min = 10; //aralar kaç dakika
    var $max_quiz_competitor = 6; //maksimum bilgi yarışmasındaki yarışmacı sayısı
    var $date = '27/02/1995'; //defualt bir tarih atıyoruz
    var $start_time = '10:00'; //default başlangıl saati
    var $end_time = '17:30'; //default yarışmanın bitiş saati
    var $lunch_time = '13:00'; //default öğle yemeği başlangıçı
    var $lunch_time_end = '14:00'; //default öğle yemeği bitişi
    var $min_type = 'H:i'; //Dakikanın gösterileceği tarih tipi
    var $quiz_min = 45; //Bilgi yarışması dakikası
    var $quiz_group = '';//veritabanına quiz gruplarını kaydedicez.
    var $poetry_group = '';//veritabanına şiir gruplarını kaydedicez.
    var $song_group = '';//veritabanına şiir gruplarını kaydedicez.
    var $oratory_group = '';//veritabanına metin canlandırma gruplarını kaydedicez.
    var $school_type = 0;//okul türü [0=>Lise,1=>Ortaokul]

    public function __construct()
    {

        $this->_ci = get_instance();
        $this->_ci->load->model('competitions/competitions_m');
        $this->_ci->load->library('Excel2');
        $this->_ci->lang->load('competitions/competitions');
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);//bu kodu yazmazsak yüksek boyutlu excellerde hata verecektir.
    }


    /**
     * @param $competition
     */

    public function initialize($competition)
    {
        $this->current_time = strtotime($competition->start_time);
        $this->date = $competition->date;
        $this->lunch_time = $competition->lunch_time;
        $this->start_time = $competition->start_time;
        $this->end_time = $competition->end_time;
        $this->lunch_time_end = $competition->lunch_time_end;
        $this->school_type = $competition->school_type;
        $this->template_name = $competition->school_type == 0 ? 'sablon.xlsx' : 'sablon-1.xls';
        $this->current_col = $competition->school_type == 0 ? 8 : 6;
    }

    /*
    * Ana fonksiyonumuz
    *
    */


    public function export($city_id = 0,$type = 'print')
    {
        $this->city_id = $city_id;
        $city = $this->_ci->competitions_m->getCompetitionCity(array('id' => $city_id));
        $competition = $this->_ci->competitions_m->getCompetition(array('id' => $city->competition_id));
        $this->initialize($competition);

        $quizes = $this->_ci->competitions_m->limit(16)->getCompetitors(array('city_id' => $city_id,'quiz' => 1,'statu' => 1));
        $poetries = $this->_ci->competitions_m->limit(16)->getCompetitors(array('city_id' => $city_id,'poetry' => 1 , 'statu' => 1));
        $oratories = $this->_ci->competitions_m->limit(16)->getCompetitors(array('city_id' => $city_id,'oratory' => 1, 'statu' => 1));
        $songs = $this->_ci->competitions_m->limit(16)->getCompetitors(array('city_id' => $city_id,'song' => 1, 'statu' => 1));

        $this->objPHPExcel = PHPExcel_IOFactory::load(UPLOAD_PATH.$this->template_name);

        $hitap = $city->manager_school.' Müdürlüğüne,';

        $this->objPHPExcel->getActiveSheet()->setCellValue('A1', $hitap);
        $this->objPHPExcel->getActiveSheet()->setCellValue('B3', 'Millî Eğitim Bakanlığı Din Öğretimi Genel Müdürlüğü tarafından düzenlenen '.$competition->doc_title.'. Arapça Bilgi ve ');

        if($this->school_type == 0)
        {
            $this->_quiz($quizes);//qıizleri oluşturuyoruz
        }
        else{
            $songs = $this->_sameTextRepeatControl($songs,'song_title'); //arka arkaya aynı metnin gelmesini kapatıyoruz
            $this->song_group = json_encode($songs);
            $this->_others($songs,'İHO ARAPÇA ÇOCUK ŞARKILARI YARIŞMASI','song_title');//Şiir yarışmasını yazdırıyoruz

        }


        $poetries = $this->_sameTextRepeatControl($poetries,'poetry_title');//arka arkaya aynı metnin gelmesini kapatıyoruz
        $oratories = $this->_sameTextRepeatControl($oratories,'oratory_title'); //arka arkaya aynı metnin gelmesini kapatıyoruz
        $this->oratory_group = json_encode($oratories);
        $this->poetry_group = json_encode($poetries);
        $this->_others($poetries,'Şiir Yarışması','poetry_title');//Şiir yarışmasını yazdırıyoruz
        $this->_others($oratories,'Metin Canlandırma Yarışması','oratory_title'); //metin canlandırma yarışmasını yazdırıyoruz

        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD

        $title = 'Yarisma-'.$competition->id.'-'.$city->code.'.xls';
        $objWriter->save(UPLOAD_PATH.'competitions/'.$title);
        $this->_ci->competitions_m->updateCompetitionCity($city->id,array('statu' => 1,'excel' => $title,'oratory_group' => $this->oratory_group,'quiz_group' => $this->quiz_group,'poetry_group' => $this->poetry_group ));

    }

    private function _quiz($quizes)
    {
        if(count($quizes) == 0)
        {
            $this->current_col++;
            $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, 'Bilgi Yarışması dalında yarışmaya katılan öğrenci bulunmadığı için bölge ');
            $this->current_col++;
            $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, 'elemelerinde bilgi yarışması yapılmayacaktır. ');
        }
        elseif(count($quizes) == 1)
        {
            $this->current_col++;
            $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, 'Bölge genelinde tek okul yarışmaya başvurduğundan '.$quizes[0]->title);
            $this->current_col++;
            $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, 'doğrudan ülke finallerine katılacaktır.');
        }
        else
        {
            $divisor = ceil(count($quizes) / $this->max_quiz_competitor);
            $group_rows = ceil(count($quizes) / $divisor);
            $quiz_group = $this->_doQuizGroup($quizes,$divisor);
            foreach($quiz_group as $group_no=>$quizes)
            {
                foreach($quizes as $key=>$quiz)
                {
                    if($key == 0)
                    {
                        $this->current_col+=2;

                        $this->current_time = $this->_timeControl($this->quiz_min * 60);
                        $last_time = $this->current_time;
                        $this->current_time += $this->quiz_min * 60;
                        $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, $this->_convertTurkishDate($this->date).' Saat '.date($this->min_type,$last_time).'-'.date($this->min_type,$this->current_time));

                        if(count($quiz_group) > 1)
                        {
                            $this->objPHPExcel->getActiveSheet()->setCellValue('B'.$this->current_col, '('.$group_no.')');
                            $this->current_col++;
                            $this->objPHPExcel->getActiveSheet()->setCellValue('B'.$this->current_col, lang('groups_'.$group_no).' GRUBU');
                        }
                    }

                    $this->current_col++;

                    $this->objPHPExcel->getActiveSheet()->setCellValue('B'.$this->current_col, '•');
                    $this->objPHPExcel->getActiveSheet()->getStyle('B'.$this->current_col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, $quiz->title);
                }
                $this->current_time += $this->timeout_min*60;
            }

            if(count($quiz_group) > 1)
            {
                $this->_quizResult($group_no,count($quizes));
            }
        }
    }

    /*
    *
    * Bilgi yarışması gruplarını ayarlar
    *
    */
    private function _doQuizGroup($arr,$divisor)
    {
        $i=1;
        $quiz_group = array();
        foreach($arr as $quiz)
        {
            $quiz_group[$i][] = $quiz;
            if($i==$divisor)
            {
                $i=1;
            }
            else
            {
                $i++;
            }
        }
        $this->quiz_group = json_encode($quiz_group);
        return $quiz_group;
    }

    /*
    *
    * Bilgi yarışmasına katılan okul sayısına göre final yarışmasını ayarlar.
    */
    private function _quizResult($group_no,$quiz_count)
    {
        $values = $this->do_quiz_result_group($group_no);
        if(!$values)
        {
            return false;
        }

        $this->current_col+=2;
        $this->current_time = $this->_timeControl($this->quiz_min * 60);
        $last_time = $this->current_time;
        $this->current_time += $this->quiz_min * 60;

        $this->objPHPExcel->getActiveSheet()->setCellValue('B'.$this->current_col,'('.($group_no+1).')');
        $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, $this->_convertTurkishDate($this->date).' Saat '.date($this->min_type,$last_time).'-'.date($this->min_type,$this->current_time));
        foreach($values['sentences'] as $sentence)
        {
            $this->current_col++;
            $this->objPHPExcel->getActiveSheet()->setCellValue('B'.$this->current_col, '•');
            $this->objPHPExcel->getActiveSheet()->getStyle('B'.$this->current_col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, $sentence);
        }
        $this->current_col+=2;
        $this->objPHPExcel->getActiveSheet()->setCellValue('B'.$this->current_col, $values['text1']);
        $this->objPHPExcel->getActiveSheet()->setCellValue('B'.(++$this->current_col), $values['text2']);
        $this->objPHPExcel->getActiveSheet()->setCellValue('B'.(++$this->current_col), $values['text3']);


    }

    public function do_quiz_result_group($group_no)
    {
        if($group_no == 2)
        {
            $arr = array(
                'A grubu birincisi',
                'B grubu birincisi',
                'A grubu ikincisi',
                'B grubu ikincisi'
            );
            $text1 = 'İlk iki yarışmada birinci ve ikinci olan okullar bölge finalinde yarışacaklardır.';
            $text2 = 'Bölge finalinde birinci olan okul ülkede bölgeyi temsil edecektir.';
            $text3='';
        }
        elseif($group_no == 3)
        {
            $arr = array(
                'A grubu birincisi',
                'B grubu birincisi',
                'C grubu birincisi',
                'En iyi ikinci'
            );
            $text1 = 'İlk üç yarışmada birinci olan okullar ile 3 grupta en yüksek puanı alan';
            $text2 = 'ikinci okul bölge finalinde yarışacaklardır.Bölge finalinde birinci olan okul ülkede';
            $text3 = 'bölgeyi temsil edecektir.';
        }
        else
        {
            return false;
        }

        return array('sentences' => $arr,'text1' => $text1,'text2' => $text2,'text3' => $text3);
    }

    private function _others($competitors,$title = '',$type='')
    {
        $this->current_col+=2;
        $this->objPHPExcel->getActiveSheet()->setCellValue('B'.$this->current_col,$title);
        $styleArray = array(
            'font' => array(
                'bold' => true
            )
        );
        $this->objPHPExcel->getActiveSheet()->getStyle('B'.$this->current_col)->applyFromArray($styleArray);
        $this->current_col++;
        if(count($competitors) == 0)
        {
            $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, $title.' dalında yarışmaya katılan öğrenci bulunmadığı için bölge ');
            $this->current_col++;
            $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, 'elemelerinde '.strtolower($title).' yapılmayacaktır. ');
        }
        elseif(count($competitors) == 1)
        {
            $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, 'Bölge genelinde tek okul yarışmaya başvurduğundan '.$competitors[0]->title);
            $this->current_col++;
            $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, 'doğrudan ülke finallerine katılacaktır.');
        }
        else
        {
            $this->current_time += $this->timeout_min * 60;
            $this->current_time = $this->_timeControl($this->timeout_min * 60 * count($competitors));
            $last_time = $this->current_time;
            $this->current_time += $this->timeout_min * 60 * count($competitors);
            $this->objPHPExcel->getActiveSheet()->setCellValue('B'.$this->current_col, $this->_convertTurkishDate($this->date).' Saat '.date($this->min_type,$last_time).'-'.date($this->min_type,$this->current_time));
            $this->current_col++;

            foreach($competitors as $comp)
            {
                $this->current_col++;
                $this->objPHPExcel->getActiveSheet()->setCellValue('B'.$this->current_col, '•');
                $this->objPHPExcel->getActiveSheet()->getStyle('B'.$this->current_col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, date($this->min_type,$last_time).'-'.date($this->min_type,$last_time+$this->timeout_min * 60).': '.$comp->title);
                $this->current_col++;
                $this->objPHPExcel->getActiveSheet()->setCellValue('C'.$this->current_col, '('.$comp->{$type}.')');
                $last_time+=$this->timeout_min * 60;
            }
        }
    }


    private function _sameTextRepeatControl($arr,$type)
    {
        /*$last_text = '';
        $new_array = array();
        $old_array = $arr;
        foreach($arr as $key=>$comp)
        {
            $statu = false;
            if($comp->{$type} != '' && $last_text == $comp->{$type} )
            {
                foreach($old_array as $key2=>$item)
                {
                    if($item->{$type} != $comp->{$type})
                    {
                        $last_text = $item->{$type};
                        $statu = true;
                        $new_array[$key2] = $item;
                        unset($old_array[$key2]);
                        break;
                    }
                }

            }

            if($statu == false)
            {
                $new_array[$key] = $comp;
                $last_text = $comp->{$type};
                unset($old_array[$key]);
            }
        }

        $new_array2 = array();
        $last_text = '';
        $i=0;
        $new_array = array_reverse($new_array);
        foreach($new_array as $key=>$item)
        {
            $next = next($new_array);
            $new_array2[] = $item;
            foreach($old_array as $key2=>$comp)
            {
                if($comp->{$type} != $next->{$type} && $comp->{$type} != $item->{$type})
                {
                    $new_array2[] = $item;
                    unset($old_array[$key2]);
                    break;
                }
            }

        }
        $new_array = array_merge($new_array2,$old_array);

        return $new_array;*/
        return $arr;
    }

    private function _timeControl($new_time = 0)
    {
        return $this->timeControl($this->current_time,$new_time);
    }


    public function timeControl($current_time,$new_time = 0)
    {
        $new_time += $current_time;
        $lunch_time = strtotime($this->lunch_time);
        $lunch_time_end = strtotime($this->lunch_time_end);
        $start_time = strtotime($this->start_time);
        $end_time = strtotime($this->end_time);
        if(($current_time <= $lunch_time && $new_time > $lunch_time ) || ($current_time> $lunch_time && $current_time <= $lunch_time_end))
        {
            return $lunch_time_end;
        }
        elseif($new_time > $end_time)
        {
            $this->date = date('d-m-Y',strtotime('+1 days',strtotime($this->date)));
            return $start_time;
        }
        else
        {
            return $current_time;
        }
    }

    public function _convertTurkishDate($date)
    {

        $aylar = array(
            'January'    =>    'Ocak',
            'February'    =>    'Şubat',
            'March'        =>    'Mart',
            'April'        =>    'Nisan',
            'May'        =>    'Mayıs',
            'June'        =>    'Haziran',
            'July'        =>    'Temmuz',
            'August'    =>    'Ağustos',
            'September'    =>    'Eylül',
            'October'    =>    'Ekim',
            'November'    =>    'Kasım',
            'December'    =>    'Aralık',
            'Monday'    =>    'Pazartesi',
            'Tuesday'    =>    'Salı',
            'Wednesday'    =>    'Çarşamba',
            'Thursday'    =>    'Perşembe',
            'Friday'    =>    'Cuma',
            'Saturday'    =>    'Cumartesi',
            'Sunday'    =>    'Pazar',
        );

        $dates = explode('/',$date);
        $date = $dates[2].'/'.$dates[1].'/'.$dates[0];

        return strtr(date("d F Y, l",strtotime($date)), $aylar);
    }

}