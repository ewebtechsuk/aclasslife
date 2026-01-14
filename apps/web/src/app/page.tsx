const featureCards = [
  {
    title: 'Private Aviation',
    description: 'Curated access to charter-ready jets and bespoke ownership.'
  },
  {
    title: 'Superyachts',
    description: 'Tailored brokerage and charter options for global cruising.'
  },
  {
    title: 'Residential Estates',
    description: 'Private listings for iconic mansions and estates.'
  }
];

export default function HomePage() {
  return (
    <div className="space-y-12">
      <section className="rounded-3xl border border-neutral-800 bg-neutral-900/60 p-12">
        <p className="text-sm uppercase tracking-[0.3em] text-neutral-400">Luxury marketplace</p>
        <h1 className="mt-4 text-4xl font-semibold leading-tight">
          Aclasslife connects elite buyers with the world&apos;s most exclusive assets.
        </h1>
        <p className="mt-4 max-w-2xl text-neutral-300">
          Explore verified listings across aviation, yachting, estates, watches and jewellery.
          Submit enquiries directly to trusted vendors and our concierge team.
        </p>
        <div className="mt-8 flex flex-wrap gap-4">
          <a
            href="/search"
            className="rounded-full bg-white px-6 py-3 text-sm font-semibold text-neutral-900"
          >
            Browse listings
          </a>
          <a
            href="/portal"
            className="rounded-full border border-neutral-600 px-6 py-3 text-sm font-semibold text-neutral-200"
          >
            Vendor portal
          </a>
        </div>
      </section>
      <section className="grid gap-6 md:grid-cols-3">
        {featureCards.map((card) => (
          <article key={card.title} className="rounded-2xl border border-neutral-800 p-6">
            <h2 className="text-lg font-semibold">{card.title}</h2>
            <p className="mt-3 text-sm text-neutral-400">{card.description}</p>
          </article>
        ))}
      </section>
    </div>
  );
}
