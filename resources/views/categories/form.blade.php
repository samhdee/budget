import React, { ReactNode, useEffect, useState } from "react";
import Button from 'react-bootstrap/Button';
import Form from 'react-bootstrap/Form';
import Alert from 'react-bootstrap/Alert';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import { Link, router } from '@inertiajs/react';

import { ICategory, ICategoryForm } from "@/types/category";
import Layout from '../includes/layout';
import { useTypedPage } from "@/hooks/use-typed-page";

interface ICatFormProps {
    category?: ICategory;
}

const CategoryForm = (props: ICatFormProps) => {
    const { category } = props;
    const [formData, setFormData] = useState<ICategoryForm | undefined>();
    const { csrf_token, errors } = useTypedPage().props;
    const [validated, setValidated] = useState(errors ? false : true);

    useEffect(() => {
        if (!category) {
            setFormData({
                id: undefined,
                name: '',
                description: '',
                color: ''
            });
            return;
        }

        setFormData({
            id: category.id,
            name: category.name,
            description: category.description,
            color: category.color,
            created_at: category.created_at,
            updated_at: category.updated_at
        });
    }, [category]);

    // Updates state with new form value
    function handleChange(e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) {
        const field = e.target;

        if (!formData || field.maxLength && field.maxLength < field.value.length) {
            return false;
        }

        setFormData({ ...formData, [field.name]: field.value });
    }

    // Sends new information and gives new values to list
    function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();

        if (!formData) {
            return;
        }

        const form = e.currentTarget;

        if (form.checkValidity() === false) {
            e.preventDefault();
            e.stopPropagation();
        }

        setValidated(true);

        router.post(
            route('cat_store'),
            {
                _token: csrf_token,
                id: formData.id,
                name: formData.name,
                color: formData.color,
                description: formData.description
            });
    }

    if (!formData) {
        return <></>;
    }

    return (
        <>
            {category
                ? <h1>Modifier {category.name}</h1>
                : <h1>Ajouter une catégorie</h1>
            }

            <div className="w-50 mt-5 mx-auto">
                <Form noValidate validated={validated} onSubmit={handleSubmit}>
                    <input type="hidden" name="id" value={category && category.id} />

                    <Form.Group controlId="cat-name">
                        <FloatingLabel label="Nom" className="mb-3">
                            <Form.Control
                                type="text"
                                name="name"
                                value={formData.name}
                                onChange={handleChange}
                                maxLength={100}
                                autoComplete="off"
                                data-protonpass-ignore="true"
                                required
                            />

                            {errors.name &&
                                <Alert
                                    variant="danger"
                                    className="mt-1 py-1 px-2 fs-6 fst-italic"
                                >
                                    {errors.name}
                                </Alert>
                            }

                            <Form.Control.Feedback type="invalid">
                                Eh oh le nom là
                            </Form.Control.Feedback>
                        </FloatingLabel>
                    </Form.Group>

                    <Form.Group className="mb-3" controlId="cat-color">
                        <Form.Label>
                            Couleur

                            <Form.Text className="ms-1 text-muted fst-italic">
                                (facultatif)
                            </Form.Text>
                        </Form.Label>

                        <Form.Control
                            name="color"
                            type="color"
                            value={formData.color ? formData.color : "#563d7c"}
                            title="Choose your color"
                            maxLength={9}
                            onChange={handleChange}
                        />

                        {errors.color &&
                            <Alert
                                variant="danger"
                                className="mt-1 py-1 px-2 fs-6 fst-italic"
                            >
                                {errors.color}
                            </Alert>
                        }
                    </Form.Group>

                    <Form.Group controlId="cat-description">
                        <FloatingLabel label="Description" className="mb-3">
                            <Form.Control
                                as="textarea"
                                name="description"
                                value={formData.description}
                                onChange={handleChange}
                                maxLength={255}
                                rows={6}
                            />

                            {errors.description &&
                                <Alert
                                    variant="danger"
                                    className="mt-1 py-1 px-2 fs-6 fst-italic"
                                >
                                    {errors.description}
                                </Alert>
                            }
                        </FloatingLabel>
                    </Form.Group>

                    <Button type="submit" className="me-2" variant="success">
                        Sauvegarder
                    </Button>

                    <Link href={route('cat_index')} className="btn btn-secondary">
                        Annuler
                    </Link>
                </Form>
            </div>
        </>
    )
}

CategoryForm.layout = (page: ReactNode) => <Layout children={page} title="Catégories" />

export default CategoryForm;