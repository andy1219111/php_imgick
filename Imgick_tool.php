<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 图片压缩累  重新封装了Imagick
 * 
 * @version 2014-07-30
 * @author andy1219111@163.com
 */
class Imgick_tool{
	
	public $obj = null;
	
	public function __construct()
	{
		//判断是否加载了该扩展
		if(!extension_loaded('Imagick'))
		{
			return false;
		}
		$this->obj = new Imagick();
	}
	/*
	 * png2jpg转换图片格式
	 * 
	 * @param string src_img 源图片路径
	 * @param string dest_img 要生成的图片的路径
	 * @return boolean 转换成共返回true  否则false
	 */
	public function png2jpg($src_img,$dest_img)
	{
		if(!is_object($this->obj))
		{
			return false;
		}
		try
		{
			$this->obj->readImage($src_img);
			if($this->obj->writeImage($dest_img))
			{
				$this->destory();
				return $dest_img;
			}
			return false;
		}
		catch (ImagickException $e)
		{
			return false;
		}
	}
	
	/*
	 * 去掉图片的profile信息
	 * 
	 * @param string src_img 源图片路径
	 * @param string dest_img 要生成的图片的路径
	 * @return boolean 转换成共返回true  否则false
	 */
	public function strip_profile($src_img)
	{
		if(!is_object($this->obj))
		{
			return false;
		}
		try
		{
			$this->obj->readImage($src_img);
			$this->obj->stripImage ();
			if($this->obj->writeImage ($src_img))
			{
				$this->destory();
				return $src_img;
			}
			return false;
		}
		catch (ImagickException $e)
		{
			return false;
		}
	}
	
	/*
	 * 设置jpg图片质量
	 * 
	 * @param string src_img 源图片路径
	 * @param string dest_img 要生成的图片的路径
	 * @return boolean 转换成共返回true  否则false
	 */
	public function set_quality($src_img,$quality = 70,$dest_img = '')
	{
		if(!is_object($this->obj))
		{
			return false;
		}
		try
		{
			$dest_img = empty($dest_img) ? $src_img : $dest_img;
			$this->obj->readImage($src_img);
			$this->obj->setImageCompression(Imagick::COMPRESSION_JPEG);
			$this->obj->setImageCompressionQuality($quality);
			if($this->obj->writeImage($dest_img))
			{
				$this->destory();
				return $dest_img;
			}
			return false;
		}
		catch (ImagickException $e)
		{
			return false;
		}
	}
	
	/*
	 * 图片瘦身
	 * 
	 * @param string src_img 源图片路径
	 * @param int quality 设置图片压缩质量
	 * @param string dest_img 要生成的图片的路径
	 * @return boolean 转换成共返回true  否则false
	 */
	public function slimming($src_img,$quality = 60,$dest_img = '')
	{
		if(!is_object($this->obj))
		{
			return false;
		}
		try
		{
			$dest_img = empty($dest_img) ? $src_img : $dest_img;
			$this->obj->readImage($src_img);
			$this->obj->setImageFormat('jpeg');
			$this->obj->setImageCompression(Imagick::COMPRESSION_JPEG);
			//将图片的质量降低到原来的60%
			$quality = $this->obj->getImageCompressionQuality() * $quality / 100;
			$this->obj->setImageCompressionQuality($quality);
			$this->obj->stripImage();
			 
			if($this->obj->writeImage($dest_img))
			{
				$this->destory();
				return $dest_img;
			}
			return false;
		}
		catch (ImagickException $e)
		{
			return false;
		}
	}
	
	/*
	 * 生成缩略图
	 * 
	 * @param string src_img 源图片路径
	 * @param int quality 设置图片压缩质量
	 * @param string dest_img 要生成的图片的路径
	 * @return boolean 转换成共返回true  否则false
	 */
	public function thumb($src_img,$width = 250,$height = '')
	{
		if(!is_object($this->obj))
		{
			return false;
		}
		try
		{
			
			$file_info = pathinfo($src_img);
			//生成缩略图名称
			$file_name = substr($file_info['basename'],0,strrpos($file_info['basename'],'.'));
			$dest_img = $file_info['dirname'] . '/' . $file_name . '_thumb.' . $file_info['extension'];
			$this->obj->readImage($src_img);
			//计算要获得缩略图的高度
			$img_width = $this->obj->getImageWidth();
			$img_height = $this->obj->getImageHeight();
			$dest_height = $img_height * ($width / $img_width);
			$this->obj->resizeImage($width, $dest_height, Imagick::FILTER_CATROM, 1, false);
			//生成图片
			if($this->obj->writeImage($dest_img))
			{
				$this->destory();
				return $dest_img;
			}
			return false;
		}
		catch (ImagickException $e)
		{
			return false;
		}
	}
	
	/*
	 * 释放资源
	 * 
	 */
	function destory()
	{
		if(is_object($this->obj))
		{
			$this->obj->clear();

			$this->obj->destroy();
		}
	}
	
}