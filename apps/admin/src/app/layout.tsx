import './globals.css';
import type { ReactNode } from 'react';

export const metadata = {
  title: 'Aclasslife Admin',
  description: 'CRM and operations console for Aclasslife.'
};

export default function RootLayout({ children }: { children: ReactNode }) {
  return (
    <html lang="en-GB">
      <body className="min-h-screen">
        <div className="mx-auto flex min-h-screen max-w-6xl flex-col px-6">
          <header className="flex items-center justify-between border-b border-stone-800 py-6">
            <div className="text-lg font-semibold">Aclasslife Admin</div>
            <nav className="flex gap-5 text-sm text-stone-300">
              <a href="/pipeline" className="hover:text-white">Pipeline</a>
              <a href="/reviews/listings" className="hover:text-white">Listing review</a>
              <a href="/reviews/vendors" className="hover:text-white">Vendor review</a>
              <a href="/commission" className="hover:text-white">Commission</a>
            </nav>
          </header>
          <main className="flex-1 py-10">{children}</main>
        </div>
      </body>
    </html>
  );
}
