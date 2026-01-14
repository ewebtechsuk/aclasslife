const modeByListing: Record<string, 'ENQUIRE' | 'RESERVE'> = {
  'jet-001': 'ENQUIRE',
  'yacht-004': 'RESERVE',
  'estate-210': 'ENQUIRE'
};

export default function ListingDetailPage({ params }: { params: { id: string } }) {
  const mode = modeByListing[params.id] ?? 'ENQUIRE';
  const actionLabel = mode === 'RESERVE' ? 'Reserve / Deposit' : 'Enquire / Make offer';

  return (
    <section className="space-y-6">
      <header className="space-y-2">
        <p className="text-xs uppercase tracking-[0.3em] text-neutral-400">Listing detail</p>
        <h1 className="text-3xl font-semibold">{params.id}</h1>
        <p className="text-neutral-300">Verified listing with concierge support and vendor insights.</p>
      </header>
      <div className="rounded-2xl border border-neutral-800 p-6">
        <h2 className="text-xl font-semibold">Key highlights</h2>
        <ul className="mt-4 list-disc space-y-2 pl-4 text-neutral-300">
          <li>Confidential pricing with verified ownership.</li>
          <li>Private tours and viewings on request.</li>
          <li>Dedicated concierge for negotiations and onboarding.</li>
        </ul>
        <button className="mt-6 rounded-full bg-white px-6 py-3 text-sm font-semibold text-neutral-900">
          {actionLabel}
        </button>
      </div>
    </section>
  );
}
