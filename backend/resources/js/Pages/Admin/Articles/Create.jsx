import React from 'react';
import { useForm, Head } from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia';

export default function Create({ categories, tags }) {
    const { data, setData, post, processing, errors } = useForm({
        title: '',
        content: '',
        excerpt: '',
        category_id: '',
        featured_image: '',
        is_featured: false,
        is_breaking: false,
        status: 'draft',
        tags: [],
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/admin/articles');
    };

    return (
        <div style={{ padding: '32px' }}>
            <Head title="Créer un article" />
            
            <h1 style={{ fontSize: '32px', marginBottom: '24px' }}>Créer un article</h1>

            <form onSubmit={handleSubmit} style={{ backgroundColor: 'white', padding: '24px', borderRadius: '8px', boxShadow: '0 1px 3px rgba(0,0,0,0.1)' }}>
                <div style={{ marginBottom: '16px' }}>
                    <label style={{ display: 'block', marginBottom: '8px', fontWeight: '600' }}>Titre</label>
                    <input
                        type="text"
                        value={data.title}
                        onChange={(e) => setData('title', e.target.value)}
                        style={{ width: '100%', padding: '8px', border: '1px solid #d1d5db', borderRadius: '4px' }}
                    />
                    {errors.title && <p style={{ color: '#ef4444', fontSize: '14px', marginTop: '4px' }}>{errors.title}</p>}
                </div>

                <div style={{ marginBottom: '16px' }}>
                    <label style={{ display: 'block', marginBottom: '8px', fontWeight: '600' }}>Catégorie</label>
                    <select
                        value={data.category_id}
                        onChange={(e) => setData('category_id', e.target.value)}
                        style={{ width: '100%', padding: '8px', border: '1px solid #d1d5db', borderRadius: '4px' }}
                    >
                        <option value="">Sélectionner une catégorie</option>
                        {categories.map((category) => (
                            <option key={category.id} value={category.id}>{category.name}</option>
                        ))}
                    </select>
                    {errors.category_id && <p style={{ color: '#ef4444', fontSize: '14px', marginTop: '4px' }}>{errors.category_id}</p>}
                </div>

                <div style={{ marginBottom: '16px' }}>
                    <label style={{ display: 'block', marginBottom: '8px', fontWeight: '600' }}>Contenu</label>
                    <textarea
                        value={data.content}
                        onChange={(e) => setData('content', e.target.value)}
                        rows="10"
                        style={{ width: '100%', padding: '8px', border: '1px solid #d1d5db', borderRadius: '4px' }}
                    />
                    {errors.content && <p style={{ color: '#ef4444', fontSize: '14px', marginTop: '4px' }}>{errors.content}</p>}
                </div>

                <div style={{ marginBottom: '16px' }}>
                    <label style={{ display: 'block', marginBottom: '8px', fontWeight: '600' }}>Extrait</label>
                    <textarea
                        value={data.excerpt}
                        onChange={(e) => setData('excerpt', e.target.value)}
                        rows="3"
                        style={{ width: '100%', padding: '8px', border: '1px solid #d1d5db', borderRadius: '4px' }}
                    />
                </div>

                <div style={{ marginBottom: '16px' }}>
                    <label style={{ display: 'block', marginBottom: '8px', fontWeight: '600' }}>Image principale (URL)</label>
                    <input
                        type="text"
                        value={data.featured_image}
                        onChange={(e) => setData('featured_image', e.target.value)}
                        style={{ width: '100%', padding: '8px', border: '1px solid #d1d5db', borderRadius: '4px' }}
                    />
                </div>

                <div style={{ marginBottom: '16px' }}>
                    <label>
                        <input
                            type="checkbox"
                            checked={data.is_featured}
                            onChange={(e) => setData('is_featured', e.target.checked)}
                            style={{ marginRight: '8px' }}
                        />
                        Article en vedette
                    </label>
                </div>

                <div style={{ marginBottom: '16px' }}>
                    <label>
                        <input
                            type="checkbox"
                            checked={data.is_breaking}
                            onChange={(e) => setData('is_breaking', e.target.checked)}
                            style={{ marginRight: '8px' }}
                        />
                        Actualité urgente
                    </label>
                </div>

                <div style={{ marginBottom: '16px' }}>
                    <label style={{ display: 'block', marginBottom: '8px', fontWeight: '600' }}>Statut</label>
                    <select
                        value={data.status}
                        onChange={(e) => setData('status', e.target.value)}
                        style={{ width: '100%', padding: '8px', border: '1px solid #d1d5db', borderRadius: '4px' }}
                    >
                        <option value="draft">Brouillon</option>
                        <option value="published">Publié</option>
                    </select>
                </div>

                <div style={{ marginBottom: '24px' }}>
                    <label style={{ display: 'block', marginBottom: '8px', fontWeight: '600' }}>Tags</label>
                    {tags.map((tag) => (
                        <label key={tag.id} style={{ display: 'inlineBlock', marginRight: '16px' }}>
                            <input
                                type="checkbox"
                                checked={data.tags.includes(tag.id)}
                                onChange={(e) => {
                                    if (e.target.checked) {
                                        setData('tags', [...data.tags, tag.id]);
                                    } else {
                                        setData('tags', data.tags.filter(id => id !== tag.id));
                                    }
                                }}
                                style={{ marginRight: '4px' }}
                            />
                            {tag.name}
                        </label>
                    ))}
                </div>

                <div style={{ display: 'flex', gap: '16px' }}>
                    <button
                        type="submit"
                        disabled={processing}
                        style={{ backgroundColor: '#3b82f6', color: 'white', padding: '12px 24px', borderRadius: '6px', border: 'none', cursor: processing ? 'not-allowed' : 'pointer' }}
                    >
                        {processing ? 'En cours...' : 'Créer'}
                    </button>
                    <button
                        type="button"
                        onClick={() => Inertia.visit('/admin/articles')}
                        style={{ backgroundColor: '#6b7280', color: 'white', padding: '12px 24px', borderRadius: '6px', border: 'none', cursor: 'pointer' }}
                    >
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    );
}
