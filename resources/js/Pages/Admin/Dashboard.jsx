import React from 'react';
import { Link } from '@inertiajs/react';

export default function Dashboard() {
    return (
        <div style={{ padding: '32px' }}>
            <h1 style={{ fontSize: '32px', marginBottom: '24px' }}>Tableau de bord Admin</h1>
            
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))', gap: '24px', marginBottom: '32px' }}>
                <div style={{ backgroundColor: 'white', padding: '24px', borderRadius: '8px', boxShadow: '0 1px 3px rgba(0,0,0,0.1)' }}>
                    <h3 style={{ margin: '0 0 16px 0', color: '#6b7280' }}>Articles</h3>
                    <p style={{ fontSize: '36px', fontWeight: 'bold', margin: 0 }}>0</p>
                </div>
                
                <div style={{ backgroundColor: 'white', padding: '24px', borderRadius: '8px', boxShadow: '0 1px 3px rgba(0,0,0,0.1)' }}>
                    <h3 style={{ margin: '0 0 16px 0', color: '#6b7280' }}>Catégories</h3>
                    <p style={{ fontSize: '36px', fontWeight: 'bold', margin: 0 }}>6</p>
                </div>
                
                <div style={{ backgroundColor: 'white', padding: '24px', borderRadius: '8px', boxShadow: '0 1px 3px rgba(0,0,0,0.1)' }}>
                    <h3 style={{ margin: '0 0 16px 0', color: '#6b7280' }}>Utilisateurs</h3>
                    <p style={{ fontSize: '36px', fontWeight: 'bold', margin: 0 }}>0</p>
                </div>
            </div>

            <div style={{ backgroundColor: 'white', padding: '24px', borderRadius: '8px', boxShadow: '0 1px 3px rgba(0,0,0,0.1)' }}>
                <h2 style={{ marginBottom: '16px' }}>Actions rapides</h2>
                <Link href="/admin/articles/create" style={{ display: 'inline-block', backgroundColor: '#3b82f6', color: 'white', padding: '12px 24px', borderRadius: '6px', textDecoration: 'none' }}>
                    Créer un article
                </Link>
            </div>
        </div>
    );
}
