<?php

namespace App\Controller;

use App\Entity\CsvData;
use App\Entity\Product;
use App\Form\CsvDataType;
use App\Repository\CsvDataRepository;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/csv/data")
 */
class CsvDataController extends AbstractController
{
    /**
     * @Route("/", name="csv_data_index", methods={"GET"})
     */
    public function index(CsvDataRepository $csvDataRepository): Response
    {
        return $this->render('csv_data/index.html.twig', [
            'csv_datas' => $csvDataRepository->findAll(),
        ]);
    }

    /**
     * @Route("/newform", name="csv_data_new_form", methods={"GET","POST"})
     */
    public function newform(Request $request): Response
    {
        return $this->render('csv_data/new.html.twig', [
        ]);
    }

    /**
     * @Route("/new", name="csv_data_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $csvDatum = new CsvData();
        $form = $this->createForm(CsvDataType::class, $csvDatum);
        $form->handleRequest($request);
        //dump($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadFilename = $form['csv_filename']->getData();
            $entityManager = $this->getDoctrine()->getManager();
            if ($uploadFilename) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $originalFilename = pathinfo($uploadFilename->getClientOriginalName(), PATHINFO_FILENAME);

                if ('xls' == $uploadFilename->guessExtension()) {
                    $inputFileType = 'Xls';
                    $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadFilename->guessExtension();
                } else {
                    $inputFileType = 'Csv';
                    $newFilename = $originalFilename.'-'.uniqid().'.csv';
                }
                try {
                    $uploadFilename->move(
                        $destination,
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $helper = new Sample();
                $fimal = $this->getParameter('kernel.project_dir').'/public/uploads/'.$newFilename;
                /** @var Csv $reader */
                $reader = IOFactory::createReader('Csv')->setDelimiter(',')
                    ->setEnclosure('"');
                //$filename = $helper->getTemporaryFilename('csv');
                $spreadsheet = $reader->load($fimal);
                $data = $spreadsheet->getActiveSheet()->toArray();
                if (count($data) > 0) {
                    $csv_data = array_slice($data, 0, 2);
                    $csvDatum->setCsvFilename($fimal);
                    $csvDatum->setCsvData(json_encode($data));
                    $entityManager->persist($csvDatum);
                    $entityManager->flush();
                }
            }
            $url = $this->generateUrl('csv_data_show', ['id' => $csvDatum->getId()]);

            return $this->redirect($url);
        }

        return $this->render('csv_data/new.html.twig', [
            'csv_datum' => $csvDatum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="csv_data_show", methods={"GET","POST"})
     */
    public function show(CsvData $csvDatum): Response
    {
        dump($csvDatum);
        $csv_data = json_decode($csvDatum->getCsvData(), true);
        dump($csv_data);
        if ($csvDatum->getCsvHeader()) {
            $csv_header_fields = [];
            foreach ($csv_data[0] as $key => $value) {
                $csv_header_fields[] = $value;
            }
        }
        $csv_header_fields_object = [];
        if ('product' == $csvDatum->getCsvType()) {
            $csv_header_fields_object = ['ignored', 'name', 'slug', 'sku', 'on_sale', 'is_in_stock', 'is_decomposable', 'stock_quantity',
                'sale_price', 'min_quantity_sale', 'max_quantity_sale', 'regular_price', 'description', 'stock_status', 'low_stock_quantity', ];
        }

        return $this->render('csv_data/show.html.twig', [
            'csv_datums' => $csvDatum->getCsvData(),
            'csv_datum' => $csvDatum,
            'csv_header_fields' => $csv_header_fields,
            'csv_header_fields_object' => $csv_header_fields_object,
        ]);
    }

    /**
     * @Route("/process/{id}", name="csv_data_process", methods={"GET","POST"})
     */
    public function process(CsvData $csvDatum, Request $request): Response
    {
        // dump($request->request->get('fields'));
        $csv_datas = json_decode($csvDatum->getCsvData(), true);
        $csv_fields = $request->request->get('fields');
        dump($csv_fields['id']);
        if ('product' == $csvDatum->getCsvType()) {
            foreach ($csv_datas as $sheetIndex => $loadedSheet) {
                if ($sheetIndex >= 1) {
                    $product = new Product();
                    $csv_header_fields_object = ['ignored', 'name', 'slug', 'sku', 'on_sale', 'is_in_stock', 'is_decomposable', 'stock_quantity',
                'sale_price', 'min_quantity_sale', 'max_quantity_sale', 'regular_price', 'description', 'stock_status', 'low_stock_quantity', ];

                    foreach ($csv_header_fields_object as  $field) {
                        if ('name' == $field) {
                            $product->setName($request->fields[$field]);
                        }
                        /*  if ($csv_data->csv_header) {
                              $product->$field = $row[$request->fields[$field]];
                          } else {
                              $product->$field = $row[$request->fields[$index]];
                          }*/
                    }
                    /* foreach ($product->getFillImport() as $index => $field) {
                         if ($data->csv_header) {
                             $product->$field = $row[$request->fields[$field]];
                         } else {
                             $product->$field = $row[$request->fields[$index]];
                         }
                     }*/
                    $product->save();
                }
            }
        }

        return $this->render('csv_data/processing.html.twig', [
        ]);
    }

    /**
     * @Route("/{id}/edit", name="csv_data_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CsvData $csvDatum): Response
    {
        $form = $this->createForm(CsvData1Type::class, $csvDatum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('csv_data_index');
        }

        return $this->render('csv_data/edit.html.twig', [
            'csv_datum' => $csvDatum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="csv_data_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CsvData $csvDatum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$csvDatum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($csvDatum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('csv_data_index');
    }
}
