// 图片数组
var imgArr = ["a.png", "b.png", "c.png", "d.png", "e.png", "f.png", "g.png", "h.png", "i.png", "j.png", "k.png", "l.png", "m.png", "n.png", "o.png", "p.png", "r.png", "s.png"]

var ind = 0;

// 头像右击事件
$(document).on("click", ".icon-right", function() {
	ind++;
	if(ind == imgArr.length) {
		ind = 0;
	}
	console.log(ind, imgArr.length)
	var picString = "/callCenter/img/" + imgArr[ind];
	$(".icon-pic").attr("src", picString)

})

// 头像左击事件
$(document).on("click", ".icon-left", function() {
	ind--;
	if(ind < 0) {
		ind = imgArr.length - 1;
	};
	console.log(ind, imgArr.length)
	var picString = "/callCenter/img/" + imgArr[ind];
	$(".icon-pic").attr("src", picString)

})

