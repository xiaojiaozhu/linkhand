<?php
/**
 * 认证用到的接口
 * 
 * @author [name] <[<email address>]>
 * 
 */
namespace Home\Controller;
use Think\Controller;

class AuthenticationController extends Controller
{
	/**
	 * [personalAuthentication 获取实名认证/个人认证信息]
	 * 杨树刚
	 * @return [type] [description]
	 */
	public function getPersonalAuthentication(){
		$u_id = I('u_id') ? I('u_id') :'';
		if(empty($u_id)){
			$output['code'] = 400;
			$output['info'] = '请登录后操作';
			exit(json_encode($output));
		}
		$map['authent_type'] = 1;
		$map['u_id'] = $u_id;
		$authentication = M('authentication')
						->field('name,id_num,id_img_just,id_img_back,u_id,authent_time,authent_state')
						->where($map)
						->find();
		if($authentication === false){
			$output['code'] = 201;
			$output['info'] = '查询失败';
		}else{
			$output['code'] = 200;
			$output['info'] = '查询成功';
			$output['data'] = _unsetNull($authentication);
		}				
		$this->ajaxReturn($output);	
	}

	/**
	 * [personalAuthentication 获取车主认证/司机认证信息]
	 * 杨树刚
	 * @return [type] [description]
	 */
	public function getDriverAuthentication(){
		$u_id = I('u_id') ? I('u_id') :'';
		if(empty($u_id)){
			$output['code'] = 400;
			$output['info'] = '请登录后操作';
			exit(json_encode($output));
		}
		$map['authent_type'] = 2;
		$map['u_id'] = $u_id;
		$authentication = M('authentication')
						->field('name,drive_num,authent_time,authent_state,drive_img,vehicle_licence,car_num,car_color,car_brand,car_imgs,car_type')
						->where($map)
						->find();
		if($authentication === false){
			$output['code'] = 201;
			$output['info'] = '查询失败';
		}else{
			$output['code'] = 200;
			$output['info'] = '查询成功';
			$output['data'] = _unsetNull($authentication);
		}				
		$this->ajaxReturn($output);	
	}

	/**
	 * [personalAuthentication 实名认证/个人认证]
	 * 杨树刚
	 * @return [type] [description]
	 */
	public function addPersonalAuthentication(){
		$u_id = I('u_id') ? I('u_id') : '';
		$data['name'] = I('name');
		$data['id_num'] = I('id_num');	//身份证
		$data['authent_type'] = 1;		//认证类型
		//上传身份证图片
		$id_img_just = $_FILES["id_img_just"];
		$data['id_img_just'] = uploadImg($id_img_just);
		$id_img_back = $_FILES["id_img_back"];
		$data['id_img_back'] = uploadImg($id_img_back);
		//
		$data['u_id'] = $u_id;
		$authentication = M('authentication') -> add($data);
		if($authentication === false){
			$output['code'] = 201;
			$output['info'] = '添加失败';
		}else{
			$output['code'] = 200;
			$output['info'] = '添加成功';
		}
		$this->ajaxReturn($output);
	}

	/**
	 * [driverAuthentication 车主认证/司机认证]
	 * 杨树刚
	 * @return [type] [description]
	 */
	public function addDriverAuthentication(){
		$u_id = I('u_id') ? I('u_id') : '';
		$data['authent_type'] = 2;
		$data['name'] = I('name');
		$data['drive_num'] = I('drive_num');	//驾驶证
		$data['car_num'] = I('car_num');
		$data['car_color'] = I('car_color');
		$data['car_brand'] = I('car_brand');
		$data['car_type'] = I('car_type');
		//上传驾驶证、行车证、车辆图片
		$drive_img = $_FILES["drive_img"];
		$data['drive_img'] = uploadImg($drive_img);
		$vehicle_licence = $_FILES["vehicle_licence"];
		$data['vehicle_licence'] = uploadImg($vehicle_licence);
		$car_imgs = $_FILES["car_imgs"];
		$data['car_imgs'] = uploadImg($car_imgs);
		$data['u_id'] = $u_id;
		$authentication = M('authentication') -> add($data);
		if($authentication === false){
			$output['code'] = 201;
			$output['info'] = '添加失败';
		}else{
			$output['code'] = 200;
			$output['info'] = '添加成功';
		}
		$this->ajaxReturn($output);
	}

	/**
	 * [uploadImg 上传图片]
	 * 杨树刚
	 * @return [type] [description]
	 */
	public function uploadImg($img){
		if(strlen($img['name'])<0){
			return false;
		}
		$upload = new \Think\Upload();
		$upload->maxSize = 3145728;	//设置上传大小
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg');	//设置上传图片类型
		$rootPath = $upload->rootPath = './';	
		$upload->savePath = 'Uploads/images/';
		$upload->subName = array('date','Ymd');
		//上传文件
		$info = $upload->upload();
		if(!$info){
			$this->error($upload->getError());
		}
		foreach ($info as $file) {
			$imgsUrl = $file['savePath'].$file['savename'];
		}
		return $imgsUrl;
	}
	
}

?>