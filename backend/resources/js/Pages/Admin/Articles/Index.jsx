import React from 'react';
import { Link } from '@inertiajs/react';

export default function Index({ articles }) {
    return (
        <div style={{ padding: '32px' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
                <h1 style={{ fontSize: '32px', margin: 0 }}>Articles</h1>
                <Link href="/admin/articles/create" style={{ backgroundColor: '#3b82f6', color: 'white', padding: '12px 24px', borderRadius: '6px', textDecoration: 'none' }}>
                    Créer un article
                </Link>
            </div>

            <div style={{ backgroundColor: 'white', borderRadius: '8px', boxShadow: '0 1px 3px rgba(0,0,0,0.1)', overflow: 'hidden' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                    <thead>
                        <tr style={{ backgroundColor: '#f9fafb', borderBottom: '1px solid #e5e7eb' }}>
                            <th style={{ padding: '12px 16px', textAlign: 'left', fontWeight: '600', color: '#374151' }}>Titre</th>
                            <th style={{ padding: '12px 16px', textAlign: 'left', fontWeight: '600', color: '#374151' }}>Catégorie</th>
                            <th style={{ padding: '12px 16px', textAlign: 'left', fontWeight: '600', color: '#374151' }}>Statut</th>
                            <th style={{ padding: '12px 16px', textAlign: 'left', fontWeight: '600', color: '#374151' }}>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {articles.data.length === 0 ? (
                            <tr>
                                <td colSpan="4" style={{ padding: '24px', textAlign: 'center', color: '#6b7280' }}>
                                    Aucun article pour le moment
                                </td>
                            </tr>
                        ) : (
                            articles.data.map((article) => (
                                <tr key={article.id} style={{ borderBottom: '1px solid #e5e7eb' }}>
                                    <td style={{ padding: '12px 16px' }}>{article.title}</td>
                                    <td style={{ padding: '12px 16px' }}>{article.category?.name || '-'}</td>
                                    <td style={{ padding: '12px 16px' }}>
                                        <span style={{ 
                                            padding: '4px 8px', 
                                            borderRadius: '4px', 
                                            fontSize: '12px',
                                            backgroundColor: article.status === 'published' ? '#10b981' : '#f59e0b',
                                            color: 'white'
                                        }}>
                                            {article.status}
                                        </span>
                                    </td>
                                    <td style={{ padding: '12px 16px' }}>
                                        <Link href={`/admin/articles/${article.id}/edit`} style={{ color: '#3b82f6', textDecoration: 'none', marginRight: '16px' }}>
                                            Modifier
                                        </Link>
                                    </td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
