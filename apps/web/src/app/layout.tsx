import './globals.css';
import type { ReactNode } from 'react';

export const metadata = {
  metadataBase: new URL('https://aclasslife.com'),
  title: 'Aclasslife | Luxury Marketplace',
  description: 'Luxury multi-vendor marketplace for jets, yachts, mansions, watches and jewellery.',
  alternates: {
    canonical: '/'
  },
  openGraph: {
    title: 'Aclasslife | Luxury Marketplace',
    description: 'Luxury multi-vendor marketplace for jets, yachts, mansions, watches and jewellery.',
    url: 'https://aclasslife.com/',
    siteName: 'Aclasslife',
    type: 'website'
  }
};

export default function RootLayout({ children }: { children: ReactNode }) {
  return (
    <html lang="en-GB">
      <body className="min-h-screen bg-neutral-950 text-neutral-100">
        <div className="mx-auto flex min-h-screen max-w-6xl flex-col px-6">
          <header className="flex items-center justify-between border-b border-neutral-800 py-6">
            <div className="text-xl font-semibold tracking-wide">Aclasslife</div>
            <nav className="flex gap-6 text-sm text-neutral-300">
              <a href="/jets" className="hover:text-white">Jets</a>
              <a href="/yachts" className="hover:text-white">Yachts</a>
              <a href="/mansions" className="hover:text-white">Mansions</a>
              <a href="/watches" className="hover:text-white">Watches</a>
              <a href="/jewellery" className="hover:text-white">Jewellery</a>
            </nav>
          </header>
          <main className="flex-1 py-10">{children}</main>
          <footer className="border-t border-neutral-800 py-6 text-xs text-neutral-500">
            © {new Date().getFullYear()} Aclasslife. Curated luxury, managed service.
          </footer>
        </div>
      </body>
    </html>
  );
}
