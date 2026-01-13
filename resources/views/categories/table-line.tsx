import Form from 'react-bootstrap/Form';
import { Link } from '@inertiajs/react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPencil } from '@fortawesome/free-solid-svg-icons';

import { ICategory } from "@/types/category";

interface TableLineProps {
    category: ICategory;
}

const TableLine = (props: TableLineProps) => {
    const { category } = props;

    return (
        <tr key={category.id}>
            <td>{category.name}</td>
            <td>{category.description}</td>

            <td className="text-center">
                <Form.Control
                    type="color"
                    value={category.color || '#000000'}
                    disabled
                />
            </td>

            <td className="text-center">
                <Link
                    className='btn btn-sm btn-primary'
                    href={route('cat_create', { 'cat_id': category.id })}
                >
                    <FontAwesomeIcon icon={faPencil} />
                </Link>
            </td>
        </tr>
    );
}

export default TableLine;