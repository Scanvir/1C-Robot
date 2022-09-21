<?php
class Controller_Admin extends Controller {

    public function __construct() {
        $this->model = new Model_Admin();
        $this->view = new View();
        
        if (!User::isAdmin()){
            header('HTTP/1.1 200');
            header('Location:' . $host . '/');
        }
    }

    function action_index() {
        $data = [];
        $this->view->generate('admin/index.php', 'admin/admin_template.php', $data);
    }
    function action_category() {
        $cat = Product::UpdateCategory();
        $data = array(
            'view' => 'category',
            'catalog' => $cat, 
            'count' => count($cat)
        );
        $this->view->generate('admin/index.php', 'admin/admin_template.php', $data);
    }
    function action_users() {
        $users = User::getAllUsers('created_at');
        $data = array(
            'view' => 'users',
            'users' => $users,
            'count' => count($users),
        );
        $this->view->generate('admin/index.php', 'admin/admin_template.php', $data);
    }
    function action_branch() {
	    Branch::UpdateBranch();
	    $branch = Branch::GetBranch();
        $data = array(
            'view' => 'branch',
            'branch' => $branch, 
            'count' => count($branch),
        );
        $this->view->generate('admin/index.php', 'admin/admin_template.php', $data);
    }
    function action_product() {
        $cnt = Product::UpdateProducts();
        $data = array(
            'view' => 'product',
            'count' => $cnt,
        );
        $this->view->generate('admin/index.php', 'admin/admin_template.php', $data);
    }
    function action_morion() {
	    $cnt = Morion::UpdateMorion();
        $data = array(
            'view' => 'morion',
            'count' => $cnt);
        
        $this->view->generate('admin/index.php', 'admin/admin_template.php', $data);
    }
    function action_active() {
        $data = array(
            'view' => 'active',
            'new' => User::newUsers(), 
            'active' => User::activeUsers(),
            'top20product' => $this->model->top20product(),
        );
        $this->view->generate('admin/index.php', 'admin/admin_template.php', $data);
    }
    function action_photo() {
	    $data = [
	        'view' => 'photo',
	        'photoCount' => $this->model->photoCount(), 
	    ];
	    
	    if (count($_FILES) > 0)
	        $data['post'] = $_FILES;
	    
	    $this->view->generate('admin/photo.php', 'admin/admin_template.php', $data);
	}
    function action_usersToXLS() {
        $header = ['ID','Email','Ім\'я', 'Телефон', 'Активація', 'Реєстрація', 'Активність', 'Адмін'];
        $result = User::getAllUsersToXLS();
        $data = ['header' => $header, 'result' => $result, 'fileName' => 'Users'];
        $this->view->generate('xlsx.php', null, $data);
    }
    function action_listex() {
	    include_once site_path.'/web/application/core/ListexApi.php';
	    $api = new \Listex\Api('012345abc');
	    
	    $result = $api->getProductsByGtin(5018066112433);
	    print_r($result);
	    echo 1;
        $result = $api->getBrands();
	}
	function action_branchReserve($get) {
	    $branch = $get['branch'];
	    $reserve = $get['reserve'];

	    $this->model->updateBranchReserve($branch, $reserve);
    }
	function action_instr(){
	    $data = $this->Auth('3e18420667ea8ca5465c97df62b0a80c');
	    $token = $data['token'];
	    $count = 0;
	    
	    $products = $this->model->getProductCode();
	    foreach ($products as $key => $product) {
            $morion = $product['Morion'];
            $code = $product['Code'];
            
            $data = $this->GetInstruction($morion, $token);
            $info = $data['info_html_ukr'];
            $hashe = $data['gfc_file_hash'];
            
            $count ++;
            //$count += $this->model->updateInstr($code, $info);
            
	    }
	    $data = array('count' => $count);
        $this->view->generate('admin/index.php', 'admin/admin_template.php', $data);
	}
	function Auth($key){
	    $endpoint = 'https://spho.pharmbase.com.ua/spauth/verify';
				
		$headers = [];
		$headers[] = 'accept: application/json';
		$headers[] = 'AccessKey: ' . $key;

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		$result = curl_exec($ch);
		
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		
		curl_close($ch);
		$data = json_decode($result, true);
		return $data;
	}
	function action_errorActivation() {
	    $noActiveUsers = user::getNoActive();
        $data = ['noActiveUsers' => count($noActiveUsers)];
        
        foreach ($noActiveUsers as $user)
        {
            print_r($user);
            $mail = new Email();
            $mail->setSubject('Activation')
                ->setTextMessage("Доброго дня, ми помітили, що у вас виникла проблема під час активації свого доступу до сайту bilaromashka.com.ua. Просимо вибачення, в нас дійсно сталася технічна проблема, та наші фахівці вже її виправили. Для активації доступу просимо вас перейти ще раз за поcиланням 'https://bilaromashka.com.ua/login/activation/?GUID=".$user['activate_hash']."'>.")
                ->setHtmlMessage("Доброго дня, ми помітили, що у вас виникла проблема під час активації свого доступу до сайту bilaromashka.com.ua.<br><br>Просимо вибачення, в нас дійсно сталася технічна проблема, та наші фахівці вже її виправили.<br><br>Для активації доступу просимо вас перейти ще раз за поcиланням <a href='https://bilaromashka.com.ua/login/activation/?GUID=".$user['activate_hash']."'>Активація аккаунту</a>.")
                ->addTo($user['email']);

            //$mail->send();
        }
        
        $this->view->generate('admin/index.php', 'admin/admin_template.php', $data);
	}
	function action_yml(){
        print_r(Product::getYml());
	}
	function action_updateRests($get){
	    if ($get){
	        $branchCode = $get['branchCode'];
	        $data = Product::GetAllRests($branchCode);
	    } else {
	        Product::UpdateRests();
	        $this->view->generate('admin/index.php', 'admin/admin_template.php', []);
	    }
	}
	function action_productWithoutPhotoToXLS(){
	    $header = ['Артикул','Товар','Виробник'];
        $result = $this->model->getProductWithoutPhotoToXLS();
        $data = ['header' => $header, 'result' => $result, 'fileName' => 'WithoutPhotos'];
        $this->view->generate('xlsx.php', null, $data);
	}
	function action_search() {
	    $data = ['search' => 1];
	    $this->view->generate('admin/index.php', 'admin/admin_template.php', $data);
	}
}