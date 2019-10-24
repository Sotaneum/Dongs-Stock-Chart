<?php
/* pChart library inclusions */
include("./class/pData.class.php");
include("./class/pDraw.class.php");
include("./class/pImage.class.php");

//외부 함수 저장
extract($_POST);
//차트를 그린다.
function drawChart($MyData, $main_title, $sub_title, $width, $height)
{
	/* Create and populate the pData object */
	$myPicture = new pImage($width, $height, $MyData);
	/* Draw the background */
	$Settings = array("R" => 170, "G" => 183, "B" => 87, "Dash" => 1, "DashR" => 190, "DashG" => 203, "DashB" => 107);
	$myPicture->drawFilledRectangle(0, 0, $width, $height, $Settings);

	/* Overlay with a gradient */
	$Settings = array("StartR" => 219, "StartG" => 231, "StartB" => 139, "EndR" => 1, "EndG" => 138, "EndB" => 68, "Alpha" => 50);
	$myPicture->drawGradientArea(0, 0, $width, $height, DIRECTION_VERTICAL, $Settings);
	$myPicture->drawGradientArea(0, 0, $width, 40, DIRECTION_VERTICAL, array("StartR" => 0, "StartG" => 0, "StartB" => 0, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 80));

	/* Add a border to the picture */
	$myPicture->drawRectangle(0, 0, $width - 1, $height - 1, array("R" => 0, "G" => 0, "B" => 0));

	/* Write the picture title */
	$myPicture->setFontProperties(array("FontName" => "./fonts/NanumGothic.ttf", "FontSize" => 14));
	$myPicture->drawText(10, 30, $main_title, array("R" => 255, "G" => 255, "B" => 255));

	/* Write the chart title */
	$myPicture->setFontProperties(array("FontName" => "./fonts/NanumGothic.ttf", "FontSize" => 11));
	$myPicture->drawText(80, 75, $sub_title, array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

	/* Draw the scale and the 1st chart */
	$myPicture->setGraphArea(60, 100, $width - 30, $height - 50);
	$myPicture->drawFilledRectangle(60, 100, $width - 30, $height - 40, array("R" => 255, "G" => 255, "B" => 255, "Surrounding" => -200, "Alpha" => 10));
	$myPicture->setFontProperties(array("FontName" => "./fonts/NanumGothic.ttf", "FontSize" => 8));
	$myPicture->drawScale(array("DrawSubTicks" => TRUE));
	$myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
	$myPicture->drawSplineChart();
	$myPicture->setShadow(FALSE);

	/* Write the chart legend */
	$myPicture->drawLegend(60, $height - 20, array("Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL));

	$myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
	$myPicture->setFontProperties(array("FontName" => "./fonts/NanumGothic.ttf", "FontSize" => 11));

	/* Write a label over the chart */
	//$myPicture->writeLabel("시가",0); 

	/* Write a label over the chart */
	//$LabelSettings = array("TitleMode"=>LABEL_TITLE_BACKGROUND,"DrawSerieColor"=>FALSE,"TitleR"=>255,"TitleG"=>255,"TitleB"=>255); 
	//$myPicture->writeLabel("시가",5,$LabelSettings); 

	/* Write a label over the chart */
	//$LabelSettings = array("TitleMode"=>LABEL_TITLE_BACKGROUND,"DrawSerieColor"=>FALSE,"TitleR"=>255,"TitleG"=>255,"TitleB"=>255); 
	//$myPicture->writeLabel(array("시가","종가"),4,$LabelSettings); 

	/* Render the picture (choose the best way) */
	$myPicture->autoOutput("../pchart/pictures/example/drawLabel.png");
}
//pChart와 연동되는 빈 프로젝트를 한다.
function createData()
{
	return new pData();
}
//출력할 데이터를 입력한다.	createData한 변수, 입력할 배열, 배열의 이름
function addData($MyData, $array, $name)
{
	$MyData->addPoints($array, $name);
}
//하단 레이블 설정	CreateData한 변수, 입력할 배열, 배열의 이름
function setBottomLabel($MyData, $array, $name)
{
	$MyData->addPoints($array, $name);
	$MyData->setSerieDescription($name, $name);
	$MyData->setAbscissa($name);
}
//왼쪽 레이블 설정 CreateData한 변수, 글자
function setLeftLabel($MyData, $text)
{
	$MyData->setAxisName(0, $text);
}
//text를 배열로 변환한다. 반환값은 2차원 배열
function TextToArray($line_text)
{
	$line_arr = explode("\n", $line_text);
	$data = array(array(), array(), array(), array(), array(), array());
	for ($i = 0; $i < count($line_arr); $i++) {
		if (strlen($line_arr[$i]) >= 10) {
			$fund = explode("\t", $line_arr[$i]);
			for ($j = 0; $j < 5; $j++) {
				$fund[$j] = str_replace(",", "", $fund[$j]);
				array_push($data[$j], $fund[$j]);
			}
			$fund[$j] = str_replace(",", "", $fund[$j]);
			array_push($data[$j], ($fund[$j] / 20));
		}
	}
	return $data;
}
//전체 데이터 출력하는 데이터 전용으로 반환
function ArrayToPData($Array, $name)
{
	$pData = createData();
	setBottomLabel($pData, $Array[0], $name[0]);
	for ($i = 1; $i < count($Array); $i++) {
		addData($pData, $Array[$i], $name[$i]);
	}
	//addData($pData,$Array[5],"거래량/20");
	setLeftLabel($pData, "단위");
	return $pData;
}
//특정 SMA 데이터만 반환한다.(날짜 데이터 없음)
function getSMADay($array, $num)
{
	$SMA = array();
	$JJG = $array[4];
	for ($i = 0; $i < count($JJG); $i++) {
		if ($num - 1 <= $i) {
			$sum = 0;
			for ($j = 0; $j < $num; $j++) {
				$sum += $JJG[$i - $j];
			}
			$sum /= $num;
			array_push($SMA, $sum);
		} else {
			array_push($SMA, VOID);
		}
	}
	return $SMA;
}
//여러 SMA 데이터를 하나의 배열로 합친다.
function getSMA($array, $num)
{
	$SMA = array($array[0]);
	for ($i = 0; $i < count($num); $i++) {
		array_push($SMA, getSMADay($array, $num[$i]));
	}
	return $SMA;
}
//화면 그리기
function CreateChart($array, $array_name, $width, $height, $main_title, $sub_title)
{
	if ($width == -1) {
		$width = 200 + (100 * count($array[0]));
	}
	if ($height == -1) {
		$height = 450;
	}
	drawChart(ArrayToPData($array, $array_name), $main_title, $sub_title, $width, $height);
}
//데이터 분석 후 배열로 저장
$array = TextToArray($data);
//자료 정리 양식
$num_ar = split(",", $num);
$SMA = getSMA($array, $num_ar);
//자료 정리 양식에 따른 제목 설정
$array_title = array("날짜");
for ($i = 0; $i < count($num_ar); $i++) {
	array_push($array_title, "SMA" . $num_ar[$i]);
}
?>
<?php
//출력
CreateChart($SMA, $array_title, -1, -1, $main_title, $sub_title);
?>