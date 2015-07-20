<?php
class PmdaRxController extends AppController {
	#public $viewPath = '/db/pmda/rx/';
	public $helpers = array('Html', 'Form', 'Js', 'Session');
    public $uses = array('Rx', 'KeggDrug', 'Kegg', 'Pmda', 'PmdaRx', 'PmdaRxUpdate');
    public $components = array('Session');
	public $paginate = array(
		'limit' => 20,
		'paramType' => 'querystring'
	);

	# test
	public function beforeFilter() {
        parent::beforeFilter();
        
        $this->Auth->allow(); # すべてのアクションを許可する
    }

    # PMDA effect
	public function effect() {
		$list['0'] = '指定しない';
		array_push($list, $this->Kegg->find('list'));
		return $list;
	}

	# PMDAを一覧/検索する
	public function index() {
		#$this->set('test', $this->Pmda->update("20150101"));
		#$this->set('test', $this->Pmda->test());

		$this->Session->write('Person.eyeColor', array('asdfsa', 'asdfsa', 'asdfsa', 'asdfsa', 'asdfsa'));
		

		#$this->render('/pmda/rx/index');
		$this->set('test', $this->Session->read('Person.eyeColor'));
	}

	# 医薬品を検索する
	public function search($id = null) {
		if (!$this->request->is('ajax')){
			#$this->render('/db/pmda/rx/search');
            #$this->redirect('/db/pmda/rx/index');
        }
        else {
        	$effect = '';
	        if($this->request->data['Pmda']['effect']) {
				$effect = $this->request->data['Pmda']['data'];
			}

			if($this->request->data['Pmda']['mode']) {
				$this->set('ids', $this->PmdaRx->findIDsByKeyword($this->request->data['Pmda']['data'], $effect));
			} else {
				$this->set('ids', $this->PmdaRx->findIDsByName($this->request->data['Pmda']['data'], $effect));
			}
$this->Session->write('Person.d', array('1', '11', '111', '1111', '11111'));
			if($ids) {
				$this->Session->write('Person.eyeColor', array('1', '11', '111', '1111', '11111'));
				$this->set('test', $this->Session->read('Person.eyeColor'));
			}
			$this->render('/db/pmda/ajax/search', 'ajax');
        }
	}

	# 指定した日についての更新情報を取得する
	public function update() {
		if ($this->request->is('ajax')){
			if($date = $this->request->data['Pmda']['date']) {
				$results = $this->PmdaRxUpdate->findDate($date);

				if(empty($results)) {
					$this->PmdaRxUpdate->download($date);
					$results = $this->PmdaRxUpdate->findDate($date);
				}
				$this->set(compact('results'));
			}
            
			$this->render('/db/pmda/ajax/update', 'ajax');
        } else {
			$results = $this->PmdaRxUpdate->findToday();

			$this->set(compact('results'));
			$this->set('title_for_layout', 'PMDA/更新情報');

			#$this->render('/db/pmda/rx/update');
        }
	}

	# 指定したIDに関連するファイルをダウンロードする
	public function download() {
		if (!$this->request->is('get')){
			$this->redirect( '/pmda/index');
		}
		#$this->autoRender = FALSE;
		$id = $this->request->query['id'];
		$status = $this->PmdaRx->download($id);

		$this->set(compact('id', 'status'));

    	$this->render('/elements/pmda/download', 'ajax');
	}
}
