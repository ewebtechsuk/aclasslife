const rules = [
  { category: 'Jets', rate: '3.5%', min: '£75,000' },
  { category: 'Yachts', rate: '4.0%', min: '£120,000' }
];

export default function CommissionPage() {
  return (
    <section className="space-y-6">
      <header>
        <h1 className="text-3xl font-semibold">Commission rules</h1>
        <p className="text-stone-300">Maintain rule-based defaults for closed-won deals.</p>
      </header>
      <div className="space-y-4">
        {rules.map((rule) => (
          <article key={rule.category} className="rounded-2xl border border-stone-800 p-5">
            <h2 className="text-lg font-semibold">{rule.category}</h2>
            <p className="text-sm text-stone-400">Rate: {rule.rate}</p>
            <p className="text-sm text-stone-400">Minimum fee: {rule.min}</p>
          </article>
        ))}
      </div>
    </section>
  );
}
