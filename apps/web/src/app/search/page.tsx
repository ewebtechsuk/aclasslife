const sampleListings = [
  { id: 'jet-001', title: 'Gulfstream G650', mode: 'Enquire' },
  { id: 'yacht-004', title: 'Silver Breeze 62m', mode: 'Reserve' },
  { id: 'estate-210', title: 'Knightsbridge Estate', mode: 'Enquire' }
];

export default function SearchPage() {
  return (
    <section className="space-y-8">
      <header>
        <h1 className="text-3xl font-semibold">Search listings</h1>
        <p className="text-neutral-300">Filter by category, location, price and availability.</p>
      </header>
      <div className="grid gap-4 md:grid-cols-3">
        {sampleListings.map((listing) => (
          <article key={listing.id} className="rounded-2xl border border-neutral-800 p-4">
            <h2 className="text-lg font-semibold">{listing.title}</h2>
            <p className="mt-2 text-sm text-neutral-400">Mode: {listing.mode}</p>
            <a
              className="mt-4 inline-flex text-sm font-semibold text-white"
              href={`/listings/${listing.id}`}
            >
              View details →
            </a>
          </article>
        ))}
      </div>
    </section>
  );
}
