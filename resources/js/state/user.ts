import { createApi, fetchBaseQuery } from '@reduxjs/toolkit/query/react';

export const accountApi = createApi({
  reducerPath: 'accountApi',
  baseQuery: fetchBaseQuery({
    baseUrl: `http://localhost:3000/user`,
    //   prepareHeaders: headers => {
    //     if(configGetJWTToken()){
    //       headers.set('jwt', configGetJWTToken());
    //     }
    //     return headers;
    //   },
  }),
  endpoints: builder => ({
    getAccount: builder.query({
      query: arg => ({
        url: `1`,
        method: 'GET',
      }),
    }),
  }),
});

export default accountApi;
