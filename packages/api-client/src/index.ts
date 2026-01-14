export const apiClient = {
  baseUrl: process.env.NEXT_PUBLIC_API_URL ?? 'https://aclasslife.com/api',
  async get<T>(path: string): Promise<T> {
    const response = await fetch(`${apiClient.baseUrl}${path}`);
    if (!response.ok) {
      throw new Error(`Request failed: ${response.status}`);
    }
    return response.json() as Promise<T>;
  }
};
