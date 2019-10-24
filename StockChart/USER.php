<?php
include("./class/pData.class.php");
include("./class/pDraw.class.php");
include("./class/pImage.class.php");
class Chart
{
	private $myPicture = null;
	private $font = "./fonts/NanumGothic.ttf";
	public function __construct($pData, $width, $height)
	{
		/*오브젝트 생성*/
		$this->$myPicture = new pImage($width, $height, $pData);

		/*배경색 설정*/
		$Settings = array("R" => 170, "G" => 183, "B" => 87, "Dash" => 1, "DashR" => 190, "DashG" => 203, "DashB" => 107);
		$this->$myPicture->drawFilledRectangle(0, 0, $width, $height, $Settings);

		/*그래디언트 오버레이*/
		$Settings = array("StartR" => 219, "StartG" => 231, "StartB" => 139, "EndR" => 1, "EndG" => 138, "EndB" => 68, "Alpha" => 50);
		$this->$myPicture->drawGradientArea(0, 0, $width, $height, DIRECTION_VERTICAL, $Settings);
		$this->$myPicture->drawGradientArea(0, 0, $width, 20, DIRECTION_VERTICAL, array("StartR" => 0, "StartG" => 0, "StartB" => 0, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 80));

		/*그림 테두리*/
		$this->$myPicture->drawRectangle(0, 0, $width - 1, $height - 1, array("R" => 0, "G" => 0, "B" => 0));

		/* 눈금 및 첫번째 차트 그리기 */
		$this->$myPicture->setGraphArea(60, 60, $width - 30, $height - 40);
		$this->$myPicture->drawFilledRectangle(60, 60, $width - 30, $height - 40, array("R" => 255, "G" => 255, "B" => 255, "Surrounding" => -200, "Alpha" => 10));
		$this->$myPicture->setFontProperties(array("FontName" => $this->$font, "FontSize" => 6));
		$this->$myPicture->drawScale(array("DrawSubTicks" => TRUE));
		$this->$myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
		$this->$myPicture->drawSplineChart();
		$this->$myPicture->setShadow(FALSE);

		/* 차트 범례 */
		$this->$myPicture->drawLegend($width - 100, $height - 20, array("Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL));

		$this->$myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
		$this->$myPicture->setFontProperties(array("FontName" => $this->$font, "FontSize" => 6));
	}

	/* 그림 제목설정 */
	public function setPicTitle($title, $size, $top, $left)
	{
		$this->$myPicture->setFontProperties(array("FontName" => $this->$font, "FontSize" => $size));
		$this->$myPicture->drawText($top, $left, $title, array("R" => 255, "G" => 255, "B" => 255));
	}

	/* 차트 제목설정 */
	public function setChartTitle($title, $size, $top, $left)
	{
		$this->$myPicture->setFontProperties(array("FontName" => $this->$font, "FontSize" => $size));
		$this->$myPicture->drawText($top, $left, $title, array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));
	}

	/* 차트위에 뭘 그리기 */
	public function addLabel($name)
	{
		$LabelSettings = array("TitleR" => 255, "TitleG" => 255, "TitleB" => 255, "DrawSerieColor" => FALSE, "TitleMode" => LABEL_TITLE_BACKGROUND, "OverrideTitle" => "Information", "ForceLabels" => array("Issue with the recording device", "New recording device"), "GradientEndR" => 220, "GradientEndG" => 255, "GradientEndB" => 220, "TitleBackgroundG" => 155);
		$this->$myPicture->writeLabel(array($name), array(1, 3), $LabelSettings);
	}

	/* 그림 렌더링 (가장 좋은 방법 선택) */
	public function show()
	{
		$myPicture->autoOutput("../pchart/pictures/example.drawLabel.caption.png");
	}
}
/*User pData*/
class UpData
{
	private $pData = null;
	public function __construct()
	{
		$this->$pData = new pData();
	}
	public function __construct1($array, $name)
	{
		$this->$pData = new pData();
		$this->addLine($array[0], $name[0]);
		$this->setBottom($name[0]);
		for ($i = 1; $i < count($array); $i++) {
			$this->addLine($array[$i], $name[$i]);
		}
		this . setLeft("단위");
	}
	public function addLine($array, $name)
	{
		$this->$pData->addPoints($array, $name);
	}
	public function setBottom($name)
	{
		$this->$pData->setSerieDescription($name, $name);
		$this->$pData->setAbscissa($name);
	}
	public function setLeft($name)
	{
		$this->$pData->setAxisName(0, $name);
	}
	public function getPData()
	{
		return $this->$pData;
	}
}
$test = new UpData();
$width = 500;
$heigh = 100;

$chart = new Chart($test->getPData(), $width, $heigh);
