import type { ButtonHTMLAttributes } from 'react';

export function PrimaryButton({ children, ...props }: ButtonHTMLAttributes<HTMLButtonElement>) {
  return (
    <button
      {...props}
      className={`rounded-full bg-white px-5 py-2 text-sm font-semibold text-neutral-900 ${
        props.className ?? ''
      }`}
    >
      {children}
    </button>
  );
}
