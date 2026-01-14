import './globals.css';
import type { ReactNode } from 'react';

export const metadata = {
  title: 'Aclasslife Portal',
  description: 'Vendor and buyer workspace for Aclasslife.'
};

export default function RootLayout({ children }: { children: ReactNode }) {
  return (
    <html lang="en-GB">
      <body className="min-h-screen">
        <div className="mx-auto flex min-h-screen max-w-6xl flex-col px-6">
          <header className="flex items-center justify-between border-b border-slate-800 py-6">
            <div className="text-lg font-semibold">Aclasslife Portal</div>
            <nav className="flex gap-5 text-sm text-slate-300">
              <a href="/dashboard" className="hover:text-white">Dashboard</a>
              <a href="/listings/new" className="hover:text-white">Create listing</a>
              <a href="/deals" className="hover:text-white">Deals</a>
            </nav>
          </header>
          <main className="flex-1 py-10">{children}</main>
        </div>
      </body>
    </html>
  );
}
