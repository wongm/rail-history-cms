var url=new Array('D131_3134.jpg',
'D166_6603.jpg',
'D611_1138.jpg',
'D618_1878.jpg',
'D635_3507.jpg',
'D716_1660.jpg',
'D769_6907.jpg',
'D769_6961.jpg',
'D770_7057.jpg',
'D846_4630.jpg',
'D861_6176.jpg',
'D866_6688.jpg',
'D866_6695.jpg',
'D890_9069.jpg',
'D897_9759.jpg',
'D955_5537.jpg',
'D962_6262.jpg',
'D963_6301.jpg',
'D966_6644.jpg',
'E100_1319.jpg',
'E100_4705.jpg',
'E100_4855.jpg',
'E100_7268.jpg',
'E100_8444.jpg',
'E100_9267.jpg',
'E101_0843.jpg',
'E101_3905.jpg',
'E101_5205.jpg',
'E101_5405.jpg',
'E101_5636.jpg',
'E101_7768.jpg',
'E101_7797.jpg',
'E101_8559.jpg',
'E101_8621.jpg',
'E102_3152.jpg',
'E102_3166.jpg',
'E102_3207.jpg',
'E102_3846.jpg',
'E102_4434.jpg',
'E102_4460.jpg',
'E102_4499.jpg',
'E102_4658.jpg',
'E102_8788.jpg',
'E104_9559.jpg',
'E104_9675.jpg',
'E104_9748.jpg',
'E104_9866.jpg',
'E105_0048.jpg',
'E105_0086.jpg',
'E105_0417.jpg',
'E108_1021.jpg');

var cap=new Array('Shunting the cement works at Waurn Ponds, 2006',
'Y classes on the oil shunt at North Shore, 2006',
'Cement train exits the Geelong Tunnel, 2008',
'Warrnambool freight passes through North Geelong, 2008',
'Warrnambool freight passes through Waurn Ponds, 2008',
'A class on a V/Line service at Corio, 2008',
'N class on a V/Line service at Corio, 2009',
'Empty log train departs Corio, 2009',
'Pacific National freight at Gheringhap, 2009',
'VLocity departs Geelong, 2009',
'Mildura freight passes through Corio, 2009',
'Shunting the Midway log siding, 2009',
'Shunting the Midway log siding, 2009',
'Pacific National broad gauge train at the Geelong Grain Loop, 2009',
'R707 on the turntable at Geelong Loco, 2009',
'Mildura freight passes through Moorabool, 2009',
'QRN freight at Wingeel, 2009',
'Pacific National freight at Wingeel, 2009',
'K153 on the Geelong Loco turntable, 2009',
'Mildura freight arrives at Gheringhap, 2009',
'Pacific National intermodal freight climbs up the Cowies Creek Valley, 2009',
'V/Line train climbs the grade out of Marshall, 2009',
'Pacific National intermodal freight climbs up the Cowies Creek Valley, 2009',
'Football crowds at South Geelong station, 2009',
'V/Line service passes through Grovedale, 2009',
'V/Line service passes through North Shore, 2009',
'POTA intermodal freight crosses the Moorabool Viaduct, 2009',
'Pacific National freight approaches North Shore, 2009',
'Pacific National freight approaches Moorabool, 2009',
'VLocity crosses the Barwon River at Breakwater, 2009',
'POTA intermodal freight passes a V/Line service at Lara, 2009 ',
'V/Line service stopped at Lara, 2009',
'SCT train drops down towards North Geelong C, 2009',
'POTA intermodal freight waiting at Gheringhap Loop, 2009',
'El Zorro intermodal freight arrives at Gheringhap, 2009',
'El Zorro intermodal freight arrives at Gheringhap, 2009',
'El Zorro intermodal freight departs Corio, 2009',
'Warrnambool freight waiting at North Geelong Yard, 2009',
'V/Line service between Little River and Lara, 2010',
'Pacific National freight between Little River and Lara, 2010',
'V/Line service between Little River and Lara, 2010',
'V/Line service departs Lara, 2010',
'VLocity departs Corio, 2010',
'Cement train departs North Geelong Yard, 2010',
'VLocity arrives into South Geelong station, 2010',
'Heritage locos on hire to El Zorro pass the signals at North Geelong C, 2010',
'Heritage locos on hire to El Zorro at the Geelong Grain Loop, 2010',
'Heritage locos on hire to El Zorro pass the signals at North Geelong C, 2010',
'El Zorro grain departs Corio, 2010',
'V/Line service approaches North Geelong, 2010',
'V/Line service passes through Manor, 2010');

$(document).ready(function(){
	var rand = Math.floor(url.length*Math.random());
	$("#randomimage").attr("src", 'images/frontpage/' + url[rand]);
	$("#randomimage").attr("alt", cap[rand]);
	$("#randomimage").attr("title", cap[rand]);	
	$("#randomcaption").html(cap[rand]);
});