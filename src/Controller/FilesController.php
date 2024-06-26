<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Files Controller
 *
 * @property \App\Model\Table\FilesTable $Files
 * @method \App\Model\Entity\File[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FilesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $files = $this->paginate($this->Files);

        $this->set(compact('files'));
    }

    /**
     * View method
     *
     * @param string|null $id File id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $file = $this->Files->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('file'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $file = $this->Files->newEmptyEntity();
        $result = $this->Authentication->getResult();
        $user = $result->getData(); 

        if ($this->request->is('post')) {
            $csvFile = $this->request->getData('csv_file');
            $type = $this->request->getData('type');
            

            if ($csvFile) { // Verificar tipo MIME del archivo
                $uploadPath = WWW_ROOT . 'files' . DS;
                $fileName = $csvFile->getClientFilename();
                
                $filePath = $uploadPath .$user->name ."_". $fileName ;
                
                 $csvFile->moveTo($filePath);
                 $Data = [
                    'path' => $filePath,
                    'user_id' => $user->id,
                    'type' => $type,
                    
                 ];

                 $file = $this->Files->patchEntity($file, $Data);
                 if ($this->Files->save($file)) {
                    $this->Flash->success(__('The file has been saved.'));
    
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The file could not be saved. Please, try again.'));
            }
            
        }
        $users = $this->Files->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('file', 'users'));
    }
    /**
     * Edit method
     *
     * @param string|null $id File id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $file = $this->Files->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $file = $this->Files->patchEntity($file, $this->request->getData());
            if ($this->Files->save($file)) {
                $this->Flash->success(__('The file has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The file could not be saved. Please, try again.'));
        }
        $users = $this->Files->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('file', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id File id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $file = $this->Files->get($id);
        if ($this->Files->delete($file)) {
            $this->Flash->success(__('The file has been deleted.'));
        } else {
            $this->Flash->error(__('The file could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
