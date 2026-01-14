const deals = [
  { id: 'deal-102', stage: 'QUALIFIED', buyer: 'Private client', listing: 'Silver Breeze 62m' },
  { id: 'deal-205', stage: 'NEGOTIATION', buyer: 'Family office', listing: 'G650 Charter' }
];

export default function DealsPage() {
  return (
    <section className="space-y-6">
      <header>
        <h1 className="text-3xl font-semibold">Deals</h1>
        <p className="text-slate-300">Track active enquiries and offers.</p>
      </header>
      <div className="space-y-4">
        {deals.map((deal) => (
          <article key={deal.id} className="rounded-2xl border border-slate-800 p-5">
            <div className="flex items-center justify-between">
              <div>
                <h2 className="text-lg font-semibold">{deal.listing}</h2>
                <p className="text-sm text-slate-400">Buyer: {deal.buyer}</p>
              </div>
              <span className="rounded-full border border-slate-700 px-3 py-1 text-xs text-slate-300">
                {deal.stage}
              </span>
            </div>
          </article>
        ))}
      </div>
    </section>
  );
}
