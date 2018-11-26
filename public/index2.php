<?php

//案例一：将活动背景图片和动态二维码图片合成一张图片
//图片一
$path_1 = 'D:\demo\demo.png';
//图片二
$path_2 = 'D:\demo\1540792191_5bd69f7f9cc18.png';

//创建图片对象
$image_1 = imagecreatefrompng($path_1);
$image_2 = screenshot($path_2);

//合成图片
//imagecopymerge ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h , int $pct )---拷贝并合并图像的一部分
//将 src_im 图像中坐标从 src_x，src_y 开始，宽度为 src_w，高度为 src_h 的一部分拷贝到 dst_im 图像中坐标为 dst_x 和 dst_y 的位置上。两图像将根据 pct 来决定合并程度，其值范围从 0 到 100。当 pct = 0 时，实际上什么也没做，当为 100 时对于调色板图像本函数和 imagecopy() 完全一样，它对真彩色图像实现了 alpha 透明。
imagecopymerge($image_1, $image_2, 0, 140, 0, 0, imagesx($image_2), imagesy($image_2), 100);
// 输出合成图片
//imagepng($image[,$filename]) — 以 PNG 格式将图像输出到浏览器或文件
$merge = 'merge.png';
var_dump(imagepng($image_1,'./merge.png'));

function screenshot($src_path){
    //创建源图的实例
    $src = imagecreatefromstring(file_get_contents($src_path));

    //裁剪开区域左上角的点的坐标
    $x = 0;
    $y = 125;
    //裁剪区域的宽和高
    $width = 240;
    $height = 80;
    //最终保存成图片的宽和高，和源要等比例，否则会变形
    $final_width = 288;
    $final_height = round($final_width * $height / $width);

    //将裁剪区域复制到新图片上，并根据源和目标的宽高进行缩放或者拉升
    $new_image = imagecreatetruecolor($final_width, $final_height);
    imagecopyresampled($new_image, $src, 0, 0, $x, $y, $final_width, $final_height, $width, $height);

    return $new_image;
}