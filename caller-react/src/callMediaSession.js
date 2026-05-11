let preparedStream = null;

export function setPreparedStream(stream) {
  if (preparedStream && preparedStream !== stream) {
    preparedStream.getTracks().forEach((track) => track.stop());
  }
  preparedStream = stream || null;
}

export function takePreparedStream() {
  const stream = preparedStream;
  preparedStream = null;
  return stream;
}

export function clearPreparedStream() {
  if (preparedStream) {
    preparedStream.getTracks().forEach((track) => track.stop());
  }
  preparedStream = null;
}
