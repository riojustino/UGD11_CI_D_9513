<?php
use Restserver \Libraries\REST_Controller;
Class Kendaraan extends REST_Controller{

    public function __construct(){
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Methods:GET,OPTIONS,POST,DELETE");
        header("Access-Control-Allow-Headers:Content-Type,Content-Length,Accept-Encoding");
        parent::__construct();
        $this->load->model('layananKendaraan');
        $this->load->library('form_validation');
    }
    public function index_get(){
        return$this->returnData($this->db->get('services')->result(),false);
    }
    public function index_post($id=null){
        $validation=$this->form_validation;
        $rule=$this->layananKendaraan->rules();
        if($id==null){
            array_push($rule,[
                'field'=>'name',
                'label'=>'name',
                'rules'=>'required'
            ],
            [
                'field'=>'price',
                'label'=>'price',
                'rules'=>'required|is_unique[services.type]'
            ]
            );
            }
            else{
                array_push($rule,
                [
                    'field'=>'price',
                    'label'=>'price',
                    'rules'=>'required'
                    ]);
                }
                $validation->set_rules($rule);
                if(!$validation->run()){
                    return$this->returnData($this->form_validation->error_array(),true);
                }
                $services=new KendaraanData();
                $services->name=$this->post('name');
                $services->price=$this->post('price');
                $services->type=$this->post('type');
                $services->created_at=$this->post('created_at');
                if($id==null){
                    $response=$this->layananKendaraan->store($services);

                }else{
                    $response=$this->layananKendaraan->update($services,$id);
                }
                return$this->returnData($response['msg'],$response['error']);
            }
            public function index_delete($id=null){
                if($id==null){
                    return $this->returnData('Parameter Id Tidak Ditemukan',true);
                }
                $response=$this->layananKendaraan->destroy($id);
                return
                $this->returnData($response['msg'],$response['error']);
            }
            public function returnData($msg,$error){
                $response['error']=$error;
                $response['message']=$msg;
                return$this->response($response);
            }
        }
        Class KendaraanData{
            public$nama;
            public$price;
            public$type;
            public$created_at;
        }