import { ReactNode } from 'react';
import Table from 'react-bootstrap/Table';
import { Link } from '@inertiajs/react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPlusCircle } from '@fortawesome/free-solid-svg-icons';

import Layout from '../includes/layout';
import { ICategory } from '@/types/category';
import TableLine from './table-line';

interface CategoriesIndexProps {
    categories?: ICategory[];
}

const CategoriesIndex = (props: CategoriesIndexProps) => {
    const { categories } = props;

    return (
        <>
            <h1>Coucou c&apos;est les catégoRIRES</h1>

            <div className='mx-auto w-75 d-flex justify-content-end'>
                <Link
                    className='btn btn-sm btn-success'
                    href={route('cat_create')}
                >
                    <FontAwesomeIcon className='me-1' icon={faPlusCircle} /> Créer
                </Link>
            </div>

            {categories && categories.length > 0 &&
                <Table className='mt-3 mx-auto w-75' striped bordered>
                    <thead>
                        <tr>
                            <th style={{ width: '12rem' }}>Nom</th>
                            <th>Description</th>
                            <th style={{ width: '5rem' }}>Couleur</th>
                            <th style={{ width: '4rem' }}></th>
                        </tr>
                    </thead>

                    <tbody>
                        {categories.map((category) => {
                            return (
                                <TableLine key={category.id} category={category} />
                            )
                        })}
                    </tbody>
                </Table>
            }
        </>
    )
}

CategoriesIndex.layout = (page: ReactNode) => <Layout children={page} title="Catégories" />

export default CategoriesIndex;