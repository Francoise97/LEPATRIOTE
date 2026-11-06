import React from 'react';

export default function Welcome() {
    return (
        <div style={{ minHeight: '100vh', backgroundColor: '#f3f4f6', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <div style={{ maxWidth: '900px', margin: '0 auto', padding: '32px' }}>
                <h1 style={{ fontSize: '36px', fontWeight: 'bold', textAlign: 'center', color: '#1f2937', marginBottom: '16px' }}>
                    Bienvenue sur Le Patriote
                </h1>
                <p style={{ textAlign: 'center', color: '#4b5563', fontSize: '18px' }}>
                    Votre site d'actualité - Laravel 12 + React ✅
                </p>
            </div>
        </div>
    );
}
