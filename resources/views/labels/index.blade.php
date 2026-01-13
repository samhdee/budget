import { ReactNode } from 'react';
import Table from 'react-bootstrap/Table';
import { Link } from '@inertiajs/react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPlusCircle } from '@fortawesome/free-solid-svg-icons';

import Layout from '../includes/layout';
import TableLine from './table-line';
import { ILabel } from '@/types/labels';

interface LabelsIndexProps {
    labels?: ILabel[];
}

const CategoriesIndex = (props: LabelsIndexProps) => {
    const { labels } = props;

    return (
        <>
            <h1>Labels</h1>

            <div className='mx-auto w-75 d-flex justify-content-end'>
                <Link
                    className='btn btn-sm btn-success'
                    href={route('labels_create')}
                >
                    <FontAwesomeIcon className='me-1' icon={faPlusCircle} /> Créer
                </Link>
            </div>

            {labels && labels.length > 0 &&
                <Table className='mt-3 mx-auto w-75' striped bordered>
                    <thead>
                        <tr>
                            <th style={{ width: '12rem' }}>Nom</th>
                            <th>Description</th>
                            <th style={{ width: '4rem' }}></th>
                        </tr>
                    </thead>

                    <tbody>
                        {labels.map((label) => {
                            return (
                                <TableLine key={label.id} label={label} />
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