<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\Time;
/**
 * Transactions Controller
 *
 * @property \App\Model\Table\TransactionsTable $Transactions
 * @method \App\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TransactionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Orders'],
        ];
        $transactions = $this->paginate($this->Transactions);

        $this->set(compact('transactions'));
    }

    /**
     * View method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $transaction = $this->Transactions->get($id, [
            'contain' => ['Orders'],
        ]);

        $this->set(compact('transaction'));
    }

    private function parseCSV($filePath)
    {
        $csv = array_map('str_getcsv', file($filePath));
        $header = array_shift($csv);
        $data = [];
        foreach ($csv as $row) {
            $data[] = array_combine($header, $row);
        }
        return $data;
    }

    public function add()
    {
        
        $transaction = $this->Transactions->newEmptyEntity();
        if ($this->request->is('post')) {
            $csvFile = $this->request->getData('csv_file');

            if ($csvFile) { // Verificar tipo MIME del archivo
                $uploadPath = WWW_ROOT . 'files' . DS;
                echo $uploadPath;
                 $fileName = $csvFile->getClientFilename();
                 $filePath = $uploadPath . $fileName;
                
                 $csvFile->moveTo($filePath);
                

                try {
                 

                //     // Procesar el archivo CSV
                    $data = $this->parseCSV( $filePath);

                //     // Guardar los datos en la entidad de CakePHP
                     foreach ($data as $row) {
                        $transaction = $this->Transactions->newEmptyEntity();
                        
                //         // Asegúrate de que los nombres de las columnas coincidan con los campos de la entidad

                $timeObject = Time::createFromFormat('d-m-Y H:i', $row['FECHA']);
               

                // Verificar si se creó correctamente
                if ($timeObject) {
                    // Obtener la fecha y hora en formato 'Y-m-d H:i:s'
                    $formattedDateTime = $timeObject->format('Y-m-d H:i:s');
                
                   
                
                  
                }
                         $mappedData = [
                           'id' => $row['ID'],
                            'date' => $formattedDateTime,
                            'type' => $row['TIPO'],
                            'amount' => $row['MONTO'],
                            'previous_amount' => $row['MONTO PREVIO'],
                            'order_id' => $row['ORDEN ID'],
                            'guide' => $row['NUMERO DE GUIA'],
                            'description' => $row['DESCRIPCIÓN'],
                            
                         ];
                         

                        
                         $transaction = $this->Transactions->patchEntity($transaction, $mappedData);
                        //  echo $transaction;
                        $errors = $transaction->getErrors();
                        debug($errors);
                     
                         if ($this->Transactions->save($transaction)) {
                            $this->Flash->success(__('Se guardo.'));
                     }else{
                        $this->Flash->error(__('No se guardo.'));
                     };


                     }

                     $this->Flash->success(__('CSV file imported successfully.'));
                 } catch (\Exception $e) {
                     $this->Flash->error(__('Error importing CSV file.'));
                 }
            } else {
                $this->Flash->error(__('Please upload a CSV file.'));
            }
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function load()
    {                    

        $csvFile = $this->request->getData('csv_file');
        $transaction = $this->Transactions->newEmptyEntity();

            if ($csvFile) {
               
            }

        $transaction = $this->Transactions->newEmptyEntity();
        if ($this->request->is('post')) {
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
        }
        $orders = $this->Transactions->Orders->find('list', ['limit' => 200])->all();
        $this->set(compact('transaction', 'orders'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $transaction = $this->Transactions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
        }
        $orders = $this->Transactions->Orders->find('list', ['limit' => 200])->all();
        $this->set(compact('transaction', 'orders'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $transaction = $this->Transactions->get($id);
        if ($this->Transactions->delete($transaction)) {
            $this->Flash->success(__('The transaction has been deleted.'));
        } else {
            $this->Flash->error(__('The transaction could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

